<script setup>
import { ref, computed, onMounted } from 'vue';
import { getXmlText, psGet, psUpdateOrderState } from '../../../utils/prestashop-api';

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
        display: '[id,id_customer,total_paid,current_state,date_add]',
    });

    const list = data?.prestashop?.orders?.order;
    const arr = list ? (Array.isArray(list) ? list : [list]) : [];

    orders.value = arr.map(o => {
        const id = String(o.id);
        const state = getXmlText(o.current_state);

        orderStateMap.value[id] = state;

        return {
            id,
            type: 'order',
            id_customer: getXmlText(o.id_customer),
            total_paid: parseFloat(o.total_paid || 0).toFixed(2),
            current_state: state,
            date_add: getXmlText(o.date_add),
        };
    });
};

/* ===================== LOAD CARTS ===================== */
const getAllCarts = async () => {
    const data = await psGet('carts', '', {
        display: '[id,id_customer,date_add]',
    });

    const list = data?.prestashop?.carts?.cart;
    const arr = list ? (Array.isArray(list) ? list : [list]) : [];

    carts.value = arr
        .filter(c => {
            const id = String(c.id);
            return !orders.value.some(o => o.id === id);
        })
        .map(c => {
            const id = String(c.id);

            orderStateMap.value[id] = 'cart';

            return {
                id,
                type: 'cart',
                id_customer: getXmlText(c.id_customer),
                total_paid: '0.00',
                current_state: 'cart',
                date_add: getXmlText(c.date_add),
            };
        });
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
const setOrderState = async (id, stateId) => {
    if (!stateId) return;

    updatingId.value = id;

    try {
        await psUpdateOrderState(id, stateId);
        orderStateMap.value[id] = stateId;

        await refresh();
    } finally {
        updatingId.value = null;
    }
};

/* ===================== HELPERS UI ===================== */
const getActiveStateId = (id) => orderStateMap.value[String(id)] || null;

const isPaid = (id) => getActiveStateId(id) === paidStateId.value;
const isCanceled = (id) => getActiveStateId(id) === canceledStateId.value;
const isCart = (id) =>
    getActiveStateId(id) === 'cart' ||
    getActiveStateId(id) === inCartStateId.value;

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
                        Status: {{ getStateLabel(getActiveStateId(item.id)) }}
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
                            :class="isCart(item.id)
                                ? 'btn-secondary'
                                : 'btn-outline-secondary'"
                            @click="setOrderState(item.id, inCartStateId)"
                        >
                            Panier
                        </button>

                        <button
                            class="btn btn-sm"
                            :class="isPaid(item.id)
                                ? 'btn-success'
                                : 'btn-outline-success'"
                            @click="setOrderState(item.id, paidStateId)"
                        >
                            Payé
                        </button>

                        <button
                            class="btn btn-sm"
                            :class="isCanceled(item.id)
                                ? 'btn-danger'
                                : 'btn-outline-danger'"
                            @click="setOrderState(item.id, canceledStateId)"
                        >
                            Annulé
                        </button>

                    </div>

                    <!-- ================= DROPDOWN ================= -->
                    <div class="mt-2">

                        <select
                            class="form-select form-select-sm"
                            :value="getActiveStateId(item.id)"
                            @change="setOrderState(item.id, $event.target.value)"
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