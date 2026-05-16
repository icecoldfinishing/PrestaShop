<script setup lang="ts">
import { computed, ref } from 'vue';
import { psGet } from '../../../utils/prestashop-api';
import {
    cart,
    psCreateOrder,
    psEnsureCustomerAddress,
    psGetCartSecureKey,
} from '../../../utils/products/product-api';
import { loggedCustomer, setLoggedCustomer } from '../../../utils/auth/auth-state';
import { psLoginCustomerWithoutPassword } from '../../../utils/auth/auth-api';
import { runOrderImport } from '../../../services/import/orders/OrderImport.service';

const emit = defineEmits(['continueShopping', 'orderSuccess', 'request-login']);

/* =====================
   LOGS
===================== */
const debugLog = ref<string[]>([]);
const addLog = (msg: string, isError = false) => {
    const time = new Date().toLocaleTimeString();
    debugLog.value.unshift(`${isError ? '❌' : 'ℹ️'} [${time}] ${msg}`);
};

const totalItems = computed(() => cart.count);
const totalPrice = computed(() => cart.total);
const isTesting = ref(false);

/* =====================
   MODAL CHECKOUT
===================== */
const showCheckoutModal = ref(false);
const checkoutTab = ref<'login' | 'register'>('login');

// Onglet LOGIN : sélection d'un compte existant
import { psGetActiveCustomersBrief } from '../../../utils/auth/auth-api';
const customerList = ref<any[]>([]);
const loadingCustomers = ref(false);
const loginError = ref('');

const loadCustomers = async () => {
    loadingCustomers.value = true;
    loginError.value = '';
    try {
        customerList.value = await psGetActiveCustomersBrief();
    } catch {
        loginError.value = 'Impossible de charger les comptes.';
    } finally {
        loadingCustomers.value = false;
    }
};

const loginWithAccount = async (email: string) => {
    try {
        // Sauvegarder panier invité
        const guestItems = [...cart.items];

        // Login
        const customer = await psLoginCustomerWithoutPassword(email);

        // Reset ancien panier
        cart.ownerId = null;
        cart.psCartId = null;

        // Connecter client
        setLoggedCustomer(customer);

        // Charger panier client
        await cart.setOwner(customer.id);

        // Réinjecter produits invités
        for (const item of guestItems) {
            await cart.add(
                {
                    id: item.id,
                    id_attribute: item.id_attribute,
                    name: item.name,
                    reference: item.reference,
                    priceTTC: item.price,
                },
                item.quantity,
                item.variants || {}
            );
        }

        showCheckoutModal.value = false;

        addLog(`Connecté : ${customer.firstname} ${customer.lastname}`);

        addLog('Compte créé. Panier transféré.');
        //await finalizeCheckout();
    } catch (e: any) {
        console.error(e);
        loginError.value = 'Échec de la connexion.';
    }
};

// Onglet REGISTER : créer un vrai compte
const registerForm = ref({
    prenom: '',
    nom: '',
    email: '',
    password: '',
    adresse: '',
});
const registerError = ref('');
const registerLoading = ref(false);

const submitRegister = async () => {
    const { prenom, nom, email, password, adresse } = registerForm.value;
    if (!prenom || !nom || !email || !password || !adresse) {
        registerError.value = 'Tous les champs sont requis.';
        return;
    }
    if (password.length < 8) {
        registerError.value = 'Le mot de passe doit contenir au moins 8 caractères.';
        return;
    }
    registerError.value = '';
    registerLoading.value = true;
    try {
        // Créer un vrai compte client PrestaShop via le service d'import
        const today = new Date().toLocaleDateString('fr-FR'); // DD/MM/YYYY
        const results = await runOrderImport([
            {
                date: today,
                nom: `${prenom} ${nom}`,
                email,
                pwd: password,
                adresse,
                achat: '',
                etat: '',
            },
        ], addLog);

        // Connexion immédiate avec le compte créé
        const guestItems = [...cart.items];

        const customer = await psLoginCustomerWithoutPassword(email);

        // Reset panier invité
        cart.ownerId = null;
        cart.psCartId = null;

        // Login
        setLoggedCustomer(customer);

        // Charger panier client
        await cart.setOwner(customer.id);

        // Réinjecter panier invité
        for (const item of guestItems) {
            await cart.add(
                {
                    id: item.id,
                    id_attribute: item.id_attribute,
                    name: item.name,
                    reference: item.reference,
                    priceTTC: item.price,
                },
                item.quantity,
                item.variants || {}
            );
        }

        showCheckoutModal.value = false;

        addLog(`Compte créé et connecté : ${prenom} ${nom}`);

        await finalizeCheckout();
    } catch (e: any) {
        registerError.value = e?.message || 'Erreur lors de la création du compte.';
        addLog(`Erreur création compte : ${e?.message}`, true);
    } finally {
        registerLoading.value = false;
    }
};

const openCheckoutModal = async () => {
    showCheckoutModal.value = true;
    checkoutTab.value = 'login';
    await loadCustomers();
};

/* =====================
   CHECKOUT
===================== */
function formatApiError(err: unknown): string {
    if (err instanceof Error && err.message.trim()) return err.message;
    if (err && typeof err === 'object' && 'response' in err) {
        const data = (err as { response?: { data?: unknown } }).response?.data;
        if (typeof data === 'string' && data.trim()) return data;
        if (data !== undefined) return String(data);
    }
    return String(err);
}

const finalizeCheckout = async () => {
    if (!loggedCustomer.value) {
        addLog('Erreur : client non connecté', true);
        return;
    }
    if (!cart.items.length) {
        addLog('Panier vide.', true);
        return;
    }

    isTesting.value = true;
    addLog('Début validation commande...');

    try {
        const addressId = await psEnsureCustomerAddress(loggedCustomer.value);
        if (!addressId) throw new Error('Adresse client introuvable / non créée.');

        await cart.syncWithPrestaShop();
        const cartId = cart.psCartId;
        if (!cartId) throw new Error('Panier PrestaShop introuvable.');

        addLog(`Panier PrestaShop #${cartId} utilisé.`);

        const cartSecureKey = await psGetCartSecureKey(cartId);
        if (!cartSecureKey) throw new Error('Impossible de lire la secure_key du panier créé.');

        const lineItems = cart.items.map((item) => ({
            productId: item.id,
            productAttributeId: item.id_attribute ?? 0,
            quantity: item.quantity,
            name: item.name,
            reference: item.reference || '',
            unitPriceTaxIncl: Number(item.price) || 0,
            unitPriceTaxExcl: Number(item.price) || 0,
        }));

        const orderId = await psCreateOrder({
            cartId,
            customerId: loggedCustomer.value.id,
            addressId,
            secureKey: cartSecureKey,
            lineItems,
        });

        if (!orderId) throw new Error('Commande refusée ou id absent dans la réponse.');
        addLog(`✅ Commande #${orderId} enregistrée.`);
        cart.clear();
        emit('orderSuccess');
    } catch (err: unknown) {
        addLog(`Checkout : ${formatApiError(err)}`, true);
        console.error('handleCheckout:', err);
    } finally {
        isTesting.value = false;
    }
};

const handleCheckout = async () => {
    if (!cart.items.length) {
        addLog('Panier vide.', true);
        return;
    }

    // Si non connecté → ouvrir modal
    if (!loggedCustomer.value) {
        await openCheckoutModal();
        return;
    }

    // Si connecté → finaliser directement
    await finalizeCheckout();
};

/* =====================
   PANIER
===================== */
const removeItem = async (cartId: string) => {
    await cart.remove(cartId);
};

const updateQuantity = async (cartId: string, value: number) => {
    await cart.setQuantity(cartId, value);
};

const clearCart = () => {
    if (confirm('Vider le panier ?')) cart.clear();
};
</script>

<template>
    <div class="cart-page container py-4">

        <!-- HEADER -->
        <div class="cart-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-cart3 me-2"></i>
                    Mon panier
                </h2>
                <p class="text-muted mb-0">
                    {{ totalItems }} article(s) dans votre panier
                </p>
            </div>

            <button type="button" class="btn btn-outline-dark rounded-pill px-4" @click="debugLog = []">
                <i class="bi bi-trash3 me-2"></i>
                Vider logs
            </button>
        </div>

        <!-- LOGS -->
        <div v-if="debugLog.length" class="logs-box mb-4">
            <div v-for="(log, i) in debugLog" :key="i" :class="{ 'text-danger': log.includes('❌') }" class="mb-1">
                <code>{{ log }}</code>
            </div>
        </div>

        <!-- EMPTY -->
        <div v-if="cart.items.length === 0" class="empty-cart text-center">
            <div class="empty-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h3 class="fw-bold mt-3">Votre panier est vide</h3>
            <p class="text-muted">Ajoutez des produits pour commencer vos achats.</p>
            <button type="button" class="btn btn-dark rounded-pill px-4 py-2 mt-2" @click="emit('continueShopping')">
                Continuer les achats
            </button>
        </div>

        <!-- CART -->
        <div v-else class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-8">
                <div class="cart-items">
                    <div v-for="item in cart.items" :key="item.cartId" class="cart-item">
                        <div class="row align-items-center g-3">
                            <!-- IMAGE -->
                            <div class="col-md-2 col-4">
                                <div class="product-image-box">
                                    <img v-if="item.imageUrl" :src="item.imageUrl" class="product-image"
                                        @error="($event.target as HTMLImageElement).style.display = 'none'" />
                                    <div v-else class="image-fallback">
                                        <i class="bi bi-image"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- INFOS -->
                            <div class="col-md-4 col-8">
                                <h6 class="fw-bold mb-1">{{ item.name }}</h6>
                                <small class="text-muted d-block mb-1">Ref : {{ item.reference }}</small>
                                <small v-for="(val, label) in item.variants" :key="String(label)"
                                    class="badge text-bg-light border me-1 mb-1">
                                    {{ label }} : {{ val }}
                                </small>
                            </div>
                            <!-- PRICE -->
                            <div class="col-md-2 col-4 text-md-center">
                                <div class="price-tag">{{ item.price.toFixed(2) }} €</div>
                            </div>
                            <!-- QUANTITY -->
                            <div class="col-md-2 col-4">
                                <input class="form-control qty-input" type="number" min="1" :value="item.quantity"
                                    @input="updateQuantity(item.cartId, Number(($event.target as HTMLInputElement).value))" />
                            </div>
                            <!-- REMOVE -->
                            <div class="col-md-2 col-4 text-end">
                                <button type="button" class="btn btn-remove" @click="removeItem(item.cartId)">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-danger rounded-pill mt-3" @click="clearCart">
                    <i class="bi bi-trash me-2"></i>
                    Vider le panier
                </button>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="fw-bold mb-4">Résumé</h4>
                    <div class="summary-row">
                        <span>Articles</span>
                        <strong>{{ totalItems }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <strong>{{ totalPrice.toFixed(2) }} €</strong>
                    </div>
                    <div class="summary-row">
                        <span>Livraison</span>
                        <strong class="text-success">Gratuite</strong>
                    </div>
                    <hr />
                    <div class="summary-total">
                        <span>Total TTC</span>
                        <span>{{ totalPrice.toFixed(2) }} €</span>
                    </div>

                    <!-- Alerte invité -->
                    <div v-if="!loggedCustomer" class="guest-notice mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Vous devrez vous connecter ou créer un compte pour finaliser votre commande.
                    </div>

                    <button type="button" class="btn btn-checkout w-100" :disabled="isTesting" @click="handleCheckout">
                        <span v-if="!isTesting">
                            <i class="bi bi-credit-card me-2"></i>
                            Valider la commande
                        </span>
                        <span v-else>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Traitement...
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mt-3 rounded-pill"
                        @click="emit('continueShopping')">
                        Continuer les achats
                    </button>
                </div>
            </div>
        </div>

        <!-- =========================================
             MODAL CHECKOUT — Se connecter / Créer compte
        ========================================= -->
        <div v-if="showCheckoutModal" class="modal-overlay" @click.self="showCheckoutModal = false">
            <div class="modal-box">

                <!-- HEADER -->
                <div class="modal-box-header">
                    <div>
                        <h5 class="fw-bold mb-0">Finaliser votre commande</h5>
                        <p class="text-muted small mb-0">Connectez-vous ou créez un compte pour continuer</p>
                    </div>
                    <button class="btn-close-modal" @click="showCheckoutModal = false">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- TABS -->
                <div class="modal-tabs">
                    <button :class="{ active: checkoutTab === 'login' }" @click="checkoutTab = 'login'">
                        <i class="bi bi-person-check me-1"></i>
                        Se connecter
                    </button>
                    <button :class="{ active: checkoutTab === 'register' }" @click="checkoutTab = 'register'">
                        <i class="bi bi-person-plus me-1"></i>
                        Créer un compte
                    </button>
                </div>

                <div class="modal-body-content">

                    <!-- ======== LOGIN TAB ======== -->
                    <div v-if="checkoutTab === 'login'">
                        <div v-if="loadingCustomers" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm me-2"></div>
                            Chargement...
                        </div>
                        <div v-else-if="loginError" class="alert alert-danger py-2">{{ loginError }}</div>
                        <div v-else>
                            <p class="small text-muted mb-2">Sélectionnez votre compte :</p>
                            <div class="account-list">
                                <button v-for="c in customerList" :key="c.id" class="account-item"
                                    @click="loginWithAccount(c.email)">
                                    <div class="account-avatar">
                                        {{ (c.firstname?.[0] ?? '?').toUpperCase() }}
                                    </div>
                                    <div class="text-start">
                                        <div class="fw-semibold">{{ c.firstname }} {{ c.lastname }}</div>
                                        <div class="small text-muted">{{ c.email }}</div>
                                    </div>
                                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                                </button>
                                <div v-if="!customerList.length" class="text-center text-muted py-3">
                                    Aucun compte disponible.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== REGISTER TAB ======== -->
                    <div v-if="checkoutTab === 'register'">
                        <p class="small text-muted mb-3">
                            Créez votre compte pour finaliser votre commande. Vos articles seront conservés.
                        </p>

                        <div v-if="registerError" class="alert alert-danger py-2 mb-3">{{ registerError }}</div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Prénom *</label>
                                <input v-model="registerForm.prenom" class="form-control form-control-sm"
                                    placeholder="Marie" :disabled="registerLoading" />
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Nom *</label>
                                <input v-model="registerForm.nom" class="form-control form-control-sm"
                                    placeholder="Dupont" :disabled="registerLoading" />
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Email *</label>
                            <input v-model="registerForm.email" type="email" class="form-control form-control-sm"
                                placeholder="marie@email.com" :disabled="registerLoading" />
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Mot de passe *</label>
                            <input v-model="registerForm.password" type="password" class="form-control form-control-sm"
                                placeholder="Min. 8 caractères" :disabled="registerLoading" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Adresse *</label>
                            <input v-model="registerForm.adresse" class="form-control form-control-sm"
                                placeholder="12 rue de la Paix, Paris" :disabled="registerLoading" />
                        </div>

                        <button class="btn btn-checkout w-100" :disabled="registerLoading" @click="submitRegister">
                            <span v-if="!registerLoading">
                                <i class="bi bi-person-plus me-2"></i>
                                Créer mon compte et valider
                            </span>
                            <span v-else>
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Création en cours...
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.cart-page {
    max-width: 1250px;
}

/* HEADER */
.cart-header h2 {
    font-size: 2rem;
}

/* LOGS */
.logs-box {
    background: #111827;
    color: white;
    padding: 16px;
    border-radius: 16px;
    max-height: 250px;
    overflow-y: auto;
}

.logs-box code {
    white-space: pre-wrap;
    word-break: break-word;
}

/* EMPTY */
.empty-cart {
    background: white;
    padding: 70px 20px;
    border-radius: 24px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

.empty-icon {
    font-size: 5rem;
    color: #ced4da;
}

/* CART ITEMS */
.cart-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cart-item {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
}

/* IMAGE */
.product-image-box {
    width: 100%;
    aspect-ratio: 1/1;
    background: #f8f9fa;
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}

.image-fallback {
    font-size: 2rem;
    color: #adb5bd;
}

/* PRICE */
.price-tag {
    font-weight: 700;
    font-size: 1.1rem;
    color: #ff6b00;
}

/* QUANTITY */
.qty-input {
    border-radius: 12px;
    text-align: center;
    font-weight: 600;
    border: 1px solid #dee2e6;
    padding: 10px;
}

.qty-input:focus {
    border-color: #ff6b00;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.15);
}

/* REMOVE */
.btn-remove {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    border: none;
    background: #fff1f2;
    color: #dc3545;
    transition: all 0.2s ease;
}

.btn-remove:hover {
    background: #dc3545;
    color: white;
}

/* SUMMARY */
.summary-card {
    background: white;
    border-radius: 24px;
    padding: 28px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    position: sticky;
    top: 20px;
}

.summary-row,
.summary-total {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
}

.summary-total {
    font-size: 1.3rem;
    font-weight: 700;
}

/* GUEST NOTICE */
.guest-notice {
    background: #fff8f0;
    border: 1px solid #ffe0b2;
    color: #e65100;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 0.85rem;
    line-height: 1.4;
}

/* BUTTON */
.btn-checkout {
    background: linear-gradient(135deg, #ff6b00, #ff8c42);
    color: white;
    border: none;
    border-radius: 16px;
    padding: 14px;
    font-weight: 700;
    transition: all 0.25s ease;
}

.btn-checkout:hover:not(:disabled) {
    transform: translateY(-2px);
    opacity: 0.95;
}

.btn-checkout:disabled {
    opacity: 0.65;
}

/* ============================
   MODAL OVERLAY
============================ */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 16px;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.modal-box {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2);
    animation: slideUp 0.25s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-box-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px 24px 0;
}

.btn-close-modal {
    background: #f8f9fa;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #6c757d;
    transition: background 0.15s;
    flex-shrink: 0;
}

.btn-close-modal:hover {
    background: #e9ecef;
}

/* TABS */
.modal-tabs {
    display: flex;
    gap: 0;
    padding: 16px 24px 0;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 0;
}

.modal-tabs button {
    flex: 1;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 10px 8px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: -2px;
}

.modal-tabs button.active {
    color: #ff6b00;
    border-bottom-color: #ff6b00;
}

/* MODAL BODY */
.modal-body-content {
    padding: 20px 24px 24px;
}

/* ACCOUNT LIST */
.account-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
    max-height: 260px;
    overflow-y: auto;
}

.account-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 12px 14px;
    cursor: pointer;
    width: 100%;
    text-align: left;
    transition: all 0.15s ease;
}

.account-item:hover {
    background: #fff8f5;
    border-color: #ff6b00;
}

.account-avatar {
    width: 36px;
    height: 36px;
    min-width: 36px;
    background: linear-gradient(135deg, #ff6b00, #ff8c42);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.85rem;
}

/* MOBILE */
@media (max-width: 768px) {
    .cart-header h2 {
        font-size: 1.5rem;
    }

    .summary-card {
        position: static;
    }

    .cart-item {
        padding: 16px;
    }

    .modal-box {
        max-width: 100%;
        height: 100%;
        border-radius: 0;
    }
    .modal-tabs {
        padding: 12px 16px 0;
    }
    .modal-body-content {
        padding: 16px;
    }
}
</style>