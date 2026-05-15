<script setup lang="ts">
import { computed, ref } from 'vue';
import { psGet } from '../../../utils/prestashop-api';
import {
    cart,
    psCreateCart,
    psCreateOrder,
    psEnsureCustomerAddress,
    psGetCartSecureKey,
    getXmlText,
    cleanId,
} from '../../../utils/products/product-api';
import { loggedCustomer } from '../../../utils/auth/auth-state';

const emit = defineEmits(['continueShopping', 'orderSuccess']);

const debugLog = ref<string[]>([]);

const totalItems = computed(() => cart.count);
const totalPrice = computed(() => cart.total);

const addLog = (msg: string, isError = false) => {
    const time = new Date().toLocaleTimeString();
    debugLog.value.unshift(`${isError ? '❌' : 'info'} [${time}] ${msg}`);
};

function formatApiError(err: unknown): string {
    if (err instanceof Error && err.message.trim()) return err.message;
    if (err && typeof err === 'object' && 'response' in err) {
        const data = (err as { response?: { data?: unknown } }).response?.data;
        if (typeof data === 'string' && data.trim()) return data;
        if (data !== undefined) return String(data);
    }
    return String(err);
}

const handleCheckout = async () => {
    if (!loggedCustomer.value) {
        addLog('Erreur : client non connecté', true);
        return;
    }
    if (loggedCustomer.value && 'guest' in loggedCustomer.value && loggedCustomer.value.guest) {
        addLog('Mode invité : connectez-vous avec un compte pour valider une commande.', true);
        return;
    }
    if (!cart.items.length) {
        addLog('Panier vide.', true);
        return;
    }

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
    } 
};

// Dans Cart.vue <script setup>

const removeItem = async (cartId: string) => {
    await cart.remove(cartId); // Ajout du await
};

const updateQuantity = async (cartId: string, value: number) => {
    await cart.setQuantity(cartId, value); // Ajout du await
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

            <button
                type="button"
                class="btn btn-outline-dark rounded-pill px-4"
                @click="debugLog = []"
            >
                <i class="bi bi-trash3 me-2"></i>
                Vider logs
            </button>
        </div>

        <!-- LOGS -->
        <div
            v-if="debugLog.length"
            class="logs-box mb-4"
        >
            <div
                v-for="(log, i) in debugLog"
                :key="i"
                :class="{ 'text-danger': log.includes('❌') }"
                class="mb-1"
            >
                <code>{{ log }}</code>
            </div>
        </div>

        <!-- EMPTY -->
        <div
            v-if="cart.items.length === 0"
            class="empty-cart text-center"
        >
            <div class="empty-icon">
                <i class="bi bi-cart-x"></i>
            </div>

            <h3 class="fw-bold mt-3">
                Votre panier est vide
            </h3>

            <p class="text-muted">
                Ajoutez des produits pour commencer vos achats.
            </p>

            <button
                type="button"
                class="btn btn-dark rounded-pill px-4 py-2 mt-2"
                @click="emit('continueShopping')"
            >
                Continuer les achats
            </button>
        </div>

        <!-- CART -->
        <div
            v-else
            class="row g-4"
        >

            <!-- LEFT -->
            <div class="col-lg-8">

                <div class="cart-items">

                    <div
                        v-for="item in cart.items"
                        :key="item.cartId"
                        class="cart-item"
                    >
                        <div class="row align-items-center g-3">

                            <!-- IMAGE -->
                            <div class="col-md-2 col-4">

                                <div class="product-image-box">

                                    <img
                                        v-if="item.imageUrl"
                                        :src="item.imageUrl"
                                        class="product-image"
                                        @error="($event.target as HTMLImageElement).style.display='none'"
                                    />

                                    <div
                                        v-else
                                        class="image-fallback"
                                    >
                                        <i class="bi bi-image"></i>
                                    </div>

                                </div>

                            </div>

                            <!-- INFOS -->
                            <div class="col-md-4 col-8">

                                <h6 class="fw-bold mb-1">
                                    {{ item.name }}
                                </h6>

                                <small class="text-muted d-block mb-1">
                                    Ref : {{ item.reference }}
                                </small>

                                <small
                                    v-for="(val, label) in item.variants"
                                    :key="String(label)"
                                    class="badge text-bg-light border me-1 mb-1"
                                >
                                    {{ label }} : {{ val }}
                                </small>

                            </div>

                            <!-- PRICE -->
                            <div class="col-md-2 col-4 text-md-center">
                                <div class="price-tag">
                                    {{ item.price.toFixed(2) }} €
                                </div>
                            </div>

                            <!-- QUANTITY -->
                            <div class="col-md-2 col-4">

                                <input
                                    class="form-control qty-input"
                                    type="number"
                                    min="1"
                                    :value="item.quantity"
                                    @input="updateQuantity(item.cartId, Number(($event.target as HTMLInputElement).value))"
                                />

                            </div>

                            <!-- REMOVE -->
                            <div class="col-md-2 col-4 text-end">

                                <button
                                    type="button"
                                    class="btn btn-remove"
                                    @click="removeItem(item.cartId)"
                                >
                                    <i class="bi bi-trash3"></i>
                                </button>

                            </div>

                        </div>
                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-outline-danger rounded-pill mt-3"
                    @click="clearCart"
                >
                    <i class="bi bi-trash me-2"></i>
                    Vider le panier
                </button>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">

                <div class="summary-card">

                    <h4 class="fw-bold mb-4">
                        Résumé
                    </h4>

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

                    <button
                        type="button"
                        class="btn btn-checkout w-100"
                        :disabled="isTesting"
                        @click="handleCheckout"
                    >
                        <span v-if="!isTesting">
                            <i class="bi bi-credit-card me-2"></i>
                            Valider la commande
                        </span>

                        <span v-else>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Traitement...
                        </span>
                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-secondary w-100 mt-3 rounded-pill"
                        @click="emit('continueShopping')"
                    >
                        Continuer les achats
                    </button>

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
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
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
    box-shadow: 0 6px 25px rgba(0,0,0,0.05);
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
    box-shadow: 0 0 0 0.2rem rgba(255,107,0,0.15);
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
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
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

.btn-checkout:hover {
    transform: translateY(-2px);
    opacity: 0.95;
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
}
</style>