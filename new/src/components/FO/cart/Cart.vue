<script setup lang="ts">
import { computed, ref } from 'vue';
import {
    cart,
    psCreateCart,
    psCreateOrder,
    psEnsureCustomerAddress,
    psGetCartSecureKey,
    psGet,
    getXmlText,
    cleanId,
} from '../../../utils/prestashop-api';
import { loggedCustomer } from '../../../utils/auth-state';

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
    <div class="cart-container container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">🛒 Panier</h2>
            <div>
                <button type="button" class="btn btn-outline-info btn-sm me-2" @click="debugLog = []">
                    Vider logs
                </button>
            </div>
        </div>

        <div v-if="debugLog.length" class="bg-dark text-white p-3 rounded mb-4 small style-logs">
            <div
                v-for="(log, i) in debugLog"
                :key="i"
                :class="{ 'text-danger': log.includes('❌') }"
            >
                <code>{{ log }}</code>
            </div>
        </div>

        <div v-if="cart.items.length === 0" class="empty-cart text-center p-5 bg-white rounded shadow-sm">
            <p class="mt-3 fs-5 text-muted">Votre panier est vide.</p>
            <button type="button" class="btn btn-primary mt-2" @click="emit('continueShopping')">
                Continuer les achats
            </button>
        </div>

        <div v-else class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="list-group list-group-flush">
                        <div
                            v-for="item in cart.items"
                            :key="item.cartId"
                            class="list-group-item p-3"
                        >
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <img
                                        :src="item.imageUrl || 'https://via.placeholder.com/100'"
                                        class="img-fluid rounded"
                                        alt=""
                                    />
                                </div>
                                <div class="col-4">
                                    <h6 class="mb-0">{{ item.name }}</h6>
                                    <small
                                        v-for="(val, label) in item.variants"
                                        :key="String(label)"
                                        class="text-muted d-block"
                                    >
                                        {{ label }}: {{ val }}
                                    </small>
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold">{{ item.price.toFixed(2) }} €</span>
                                </div>
                                <div class="col-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"></span>
                                        <input
                                            class="form-control"
                                            type="number"
                                            min="1"
                                            :value="item.quantity"
                                            @input="updateQuantity(item.cartId, Number(($event.target as HTMLInputElement).value))"
                                        />
                                    </div>
                                </div>
                                <div class="col-2 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        @click="removeItem(item.cartId)"
                                    >
                                        Retirer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-link text-danger p-0 mb-3" @click="clearCart">
                    Vider le panier
                </button>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Résumé</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Articles ({{ totalItems }})</span>
                            <span>{{ totalPrice.toFixed(2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison</span>
                            <span class="text-success">—</span>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total TTC</span>
                            <span class="h5 text-primary">{{ totalPrice.toFixed(2) }} €</span>
                        </div>

                        <button
                            type="button"
                            class="btn btn-success w-100 fw-bold py-2"
                            :disabled="isTesting"
                            @click="handleCheckout"
                        >
                            {{ isTesting ? 'En cours…' : 'Valider la commande' }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-outline-secondary w-100 mt-2 btn-sm"
                            @click="emit('continueShopping')"
                        >
                            Continuer les achats
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.cart-container {
    max-width: 1000px;
}
.style-logs code {
    white-space: pre-wrap;
    word-break: break-word;
}
.card {
    border: none;
    border-radius: 12px;
}
.list-group-item {
    border-left: none;
    border-right: none;
}
.list-group-item:first-child {
    border-top: none;
}
</style>
