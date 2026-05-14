<script setup>
import { ref, computed, onMounted } from 'vue';
import { getXmlText, psGet, psUpdateOrderState, cleanId, psEnsureCustomerAddress, psGetCartSecureKey, psCreateOrder, psLoadCartItems } from '../../../utils/prestashop-api';

const orders = ref([]);
const carts = ref([]);
const orderStates = ref([]);
const loading = ref(false);
const updatingId = ref(null);

const orderStateMap = ref({});

const normalize = (v) => String(v || '').trim().toLowerCase();

/* ===================== STATE NAME ===================== */
const getStateName = (state) => {
    const name = state?.name?.language;
    return Array.isArray(name)
        ? getXmlText(name[0])
        : getXmlText(name);
};

/* ===================== LOAD ORDERS ===================== */
const getAllOrders = async () => {
    const data = await psGet('orders', '', {
        display: '[id,id_customer,total_paid,current_state,date_add,id_cart]',
    });

    const list = data?.prestashop?.orders?.order;
    const arr = list ? (Array.isArray(list) ? list : [list]) : [];

    orders.value = arr.map(o => {
        const id = String(o.id);
        const state = getXmlText(o.current_state);

        orderStateMap.value[`order-${id}`] = state;

        return {
            id,
            type: 'order',
            id_cart: getXmlText(o.id_cart),
            id_customer: getXmlText(o.id_customer),
            total_paid: parseFloat(o.total_paid || 0).toFixed(2),
            current_state: state,
            date_add: getXmlText(o.date_add),
        };
    });
};

/* ===================== LOAD CARTS ===================== */
/* ===================== LOAD CARTS ===================== */
const getAllCarts = async () => {
    // On récupère 'full' pour avoir les détails des lignes (associations)
    const data = await psGet('carts', '', {
        display: 'full',
    });

    const list = data?.prestashop?.carts?.cart;
    const arr = list ? (Array.isArray(list) ? list : [list]) : [];

    const orderCartIds = orders.value.map(o => String(o.id_cart)).filter(id => id);

    // Utilisation d'une fonction asynchrone pour traiter chaque panier
    const processedCarts = await Promise.all(arr
        .filter(c => !orderCartIds.includes(String(c.id)))
        .map(async (c) => {
            const id = String(c.id);
            orderStateMap.value[`cart-${id}`] = 'cart';

            // Calcul du total en utilisant psLoadCartItems pour inclure les taxes et impacts de déclinaisons
            let calculatedTotal = 0;
            try {
                const items = await psLoadCartItems(id);
                calculatedTotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            } catch (err) {
                console.error(`Erreur chargement des lignes du panier ${id}:`, err);
            }

            return {
                id,
                type: 'cart',
                id_customer: getXmlText(c.id_customer),
                total_paid: calculatedTotal.toFixed(2),
                current_state: 'cart',
                date_add: getXmlText(c.date_add),
            };
        }));

    carts.value = processedCarts;
};

/* ===================== ORDER STATES ===================== */
const getAllOrderStates = async () => {
    const data = await psGet('order_states', '', { display: 'full' });

    const list = data?.prestashop?.order_states?.order_state;
    const arr = list ? (Array.isArray(list) ? list : [list]) : [];

    orderStates.value = arr.map(s => ({
        id: String(s.id),
        name: getStateName(s),
    }));
};

/* ===================== FIND STATES ===================== */
const findStateId = (keywords) => {
    return orderStates.value.find(s =>
        keywords.some(k => normalize(s.name).includes(normalize(k)))
    )?.id || null;
};

const inCartStateId = computed(() =>
    findStateId(['panier', 'cart'])
);

const paidStateId = computed(() =>
    findStateId(['paiement accepté', 'payment accepted'])
);

const canceledStateId = computed(() =>
    findStateId(['annulé', 'cancel', 'canceled', 'annule'])
);

/* ===================== UPDATE STATE ===================== */
const setOrderState = async (id, type, stateId) => {
    if (!stateId) return;

    updatingId.value = `${type}-${id}`;

    try {
        if (type === 'cart') {
            if (stateId !== inCartStateId.value && stateId !== 'cart') {
                const cartObj = carts.value.find(c => c.id === id);
                if (cartObj) {
                    const addressId = await psEnsureCustomerAddress({ id: cartObj.id_customer });
                    const cartSecureKey = await psGetCartSecureKey(id);
                    
                    const items = await psLoadCartItems(id);
                    const lineItems = items.map(item => ({
                        productId: item.id,
                        productAttributeId: item.id_attribute ?? 0,
                        quantity: item.quantity,
                        name: item.name,
                        reference: item.reference || '',
                        unitPriceTaxIncl: Number(item.price) || 0,
                        unitPriceTaxExcl: Number(item.price) || 0,
                    }));

                    const newOrderId = await psCreateOrder({
                        cartId: id,
                        customerId: cartObj.id_customer,
                        addressId: addressId || 1,
                        secureKey: cartSecureKey,
                        lineItems: lineItems
                    });

                    // Une fois la commande créée avec le statut par défaut, on met à jour vers le statut désiré
                    await psUpdateOrderState(newOrderId, stateId);
                }
            }
        } else {
            await psUpdateOrderState(id, stateId);
            orderStateMap.value[`order-${id}`] = stateId;
        }

        await refresh();
    } catch(err) {
        console.error("Erreur setOrderState:", err);
    } finally {
        updatingId.value = null;
    }
};

/* ===================== HELPERS UI ===================== */
const getActiveStateId = (id, type) => orderStateMap.value[`${type}-${id}`] || null;

const isPaid = (id, type) => getActiveStateId(id, type) === paidStateId.value;
const isCanceled = (id, type) => getActiveStateId(id, type) === canceledStateId.value;
const isCart = (id, type) =>
    getActiveStateId(id, type) === 'cart' ||
    getActiveStateId(id, type) === inCartStateId.value;

/* ===================== LABEL ===================== */
const getStateLabel = (stateId) => {
    if (stateId === 'cart') return 'Dans le panier';

    return orderStates.value.find(s => s.id === String(stateId))?.name || 'Inconnu';
};

/* ===================== LIST ===================== */
const allItems = computed(() => [
    ...carts.value,
    ...orders.value,
]);

/* ===================== REFRESH ===================== */
const refresh = async () => {
    loading.value = true;

    try {
        await getAllOrders();
        await getAllCarts();
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await getAllOrderStates();
    await refresh();
});
</script>

<template>
<div class="container py-4">

    <h2 class="fw-bold mb-3">Panier + Commandes</h2>

    <div v-if="allItems.length" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <div class="col" v-for="item in allItems" :key="item.type + item.id">

            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold">
                        {{ item.type === 'cart' ? '🛒 Cart' : '📦 Order' }} #{{ item.id }}
                    </h5>

                    <p class="text-muted mb-1">
                        Customer ID: {{ item.id_customer }}
                    </p>

                    <p class="mb-1">
                        Status: {{ getStateLabel(getActiveStateId(item.id, item.type)) }}
                    </p>

                    <p class="text-primary fw-bold">
                        {{ item.total_paid }} €
                    </p>

                    <p class="text-muted small">
                        {{ item.date_add }}
                    </p>

                    <!-- ================= BUTTONS ================= -->
                    <div class="d-flex gap-2 mt-3">

                        <button
                            class="btn btn-sm"
                            :class="isCart(item.id, item.type)
                                ? 'btn-secondary'
                                : 'btn-outline-secondary'"
                            @click="setOrderState(item.id, item.type, inCartStateId)"
                        >
                            Panier
                        </button>

                        <button
                            class="btn btn-sm"
                            :class="isPaid(item.id, item.type)
                                ? 'btn-success'
                                : 'btn-outline-success'"
                            @click="setOrderState(item.id, item.type, paidStateId)"
                        >
                            Payé
                        </button>

                        <button
                            class="btn btn-sm"
                            :class="isCanceled(item.id, item.type)
                                ? 'btn-danger'
                                : 'btn-outline-danger'"
                            @click="setOrderState(item.id, item.type, canceledStateId)"
                        >
                            Annulé
                        </button>

                    </div>

                    <!-- ================= DROPDOWN ================= -->
                    <div class="mt-2">

                        <select
                            class="form-select form-select-sm"
                            :value="getActiveStateId(item.id, item.type)"
                            @change="setOrderState(item.id, item.type, $event.target.value)"
                        >
                            <option value="">Statut</option>

                            <option :value="inCartStateId">
                                Panier
                            </option>

                            <option :value="paidStateId">
                                Paiement accepté
                            </option>

                            <option :value="canceledStateId">
                                Annulé
                            </option>

                        </select>

                    </div>

                </div>
            </div>

        </div>

    </div>

    <div v-else class="text-center text-muted py-5">
        <div v-if="loading" class="spinner-border"></div>
        <div v-else>Aucun panier / commande</div>
    </div>

</div>
</template>