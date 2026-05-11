<script setup>
import { ref, computed, onMounted } from 'vue';
import { getXmlText, psGet, psUpdateOrderState } from '../../utils/prestashop-api';

const orders = ref([]);
const orderStates = ref([]);
const loading = ref(false);
const updatingId = ref(null);
const errorMsg = ref('');

/* ===================== SYNC STATE MAP ===================== */
const orderStateMap = ref({});

const normalize = (value) => String(value || '').trim().toLowerCase();

/* ===================== STATE NAME ===================== */
const getStateName = (state) => {
    const name = state?.name?.language;
    if (Array.isArray(name)) {
        return getXmlText(name[0]);
    }
    return getXmlText(name);
};

/* ===================== ORDERS ===================== */
const getAllOrders = async () => {
    loading.value = true;

    try {
        const data = await psGet('orders', '', {
            display: '[id,id_customer,total_paid,current_state,date_add]',
        });

        const orderData = data?.prestashop?.orders?.order;

        if (!orderData) {
            orders.value = [];
            return;
        }

        const ordersArray = Array.isArray(orderData)
            ? orderData
            : [orderData];

        orders.value = ordersArray.map((o) => {
            const id = String(o.id);
            const state = getXmlText(o.current_state);

            // 🔥 sync state map
            orderStateMap.value[id] = state;

            return {
                id,
                id_customer: getXmlText(o.id_customer),
                total_paid: parseFloat(o.total_paid || 0).toFixed(2),
                current_state: state,
                date_add: getXmlText(o.date_add),
            };
        });

    } catch (error) {
        console.error(error);
        errorMsg.value = 'Erreur chargement commandes';
    } finally {
        loading.value = false;
    }
};

/* ===================== ORDER STATES ===================== */
const getAllOrderStates = async () => {
    try {
        const data = await psGet('order_states', '', {
            display: 'full',
        });

        const stateData = data?.prestashop?.order_states?.order_state;

        if (!stateData) {
            orderStates.value = [];
            return;
        }

        const stateArray = Array.isArray(stateData)
            ? stateData
            : [stateData];

        orderStates.value = stateArray.map((state) => ({
            id: String(state.id),
            name: getStateName(state),
        }));

    } catch (error) {
        console.error(error);
    }
};

const stateOptions = computed(() => orderStates.value);

/* ===================== FIND STATES ===================== */
const findStateId = (keywords) => {
    const match = orderStates.value.find((state) => {
        const label = normalize(state.name);
        return keywords.some((k) => label.includes(k));
    });

    return match ? match.id : null;
};

const paidStateId = computed(() => findStateId(['paiement', 'pay', 'paid']));
const failedStateId = computed(() => findStateId(['echec', 'fail', 'failed','error','err']));
const canceledStateId = computed(() => findStateId(['annul', 'cancel']));

/* ===================== CORE SYNC FUNCTION ===================== */
const setOrderState = async (orderId, stateId) => {
    if (!stateId) {
        alert('Etat indisponible');
        return;
    }

    updatingId.value = orderId;

    try {
        await psUpdateOrderState(orderId, stateId);

        orderStateMap.value[String(orderId)] = stateId;

        await getAllOrders();

    } catch (error) {
        console.error(error);
        alert('Erreur update status');
    } finally {
        updatingId.value = null;
    }
};

/* ===================== LABEL ===================== */
const getStateLabel = (stateId) => {
    const state = orderStates.value.find(s => s.id === String(stateId));
    return state ? state.name : 'Inconnu';
};

onMounted(async () => {
    await getAllOrderStates();
    await getAllOrders();
});
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Order List</h2>

        <div v-if="orders.length" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <div class="col" v-for="order in orders" :key="order.id">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold">Order #{{ order.id }}</h5>

                        <p class="text-muted mb-1">
                            Customer ID: {{ order.id_customer }}
                        </p>

                        <!-- STATUS -->
                        <p class="mb-1">
                            Status: {{ getStateLabel(orderStateMap[order.id]) }}
                        </p>

                        <p class="text-primary fw-bold">
                            ${{ order.total_paid }}
                        </p>

                        <p class="text-muted small">
                            {{ order.date_add }}
                        </p>

                        <!-- ================= BUTTONS SYNC ================= -->
                        <div class="d-flex flex-wrap gap-2 mt-3">

                            <button
                                class="btn btn-sm"
                                :class="orderStateMap[order.id] === paidStateId ? 'btn-success' : 'btn-outline-success'"
                                :disabled="updatingId === order.id"
                                @click="setOrderState(order.id, paidStateId)"
                            >
                                Paiement OK
                            </button>

                            <button
                                class="btn btn-sm"
                                :class="orderStateMap[order.id] === failedStateId ? 'btn-warning' : 'btn-outline-warning'"
                                :disabled="updatingId === order.id"
                                @click="setOrderState(order.id, failedStateId)"
                            >
                                Échec
                            </button>

                            <button
                                class="btn btn-sm"
                                :class="orderStateMap[order.id] === canceledStateId ? 'btn-danger' : 'btn-outline-danger'"
                                :disabled="updatingId === order.id"
                                @click="setOrderState(order.id, canceledStateId)"
                            >
                                Annulé
                            </button>

                        </div>

                        <!-- ================= SELECT SYNC ================= -->
                        <div class="mt-2">

                            <label class="form-label small text-muted">
                                Changer statut
                            </label>

                            <select
                                class="form-select form-select-sm"
                                :value="orderStateMap[order.id]"
                                :disabled="updatingId === order.id"
                                @change="setOrderState(order.id, $event.target.value)"
                            >
                                <option value="">-- Select --</option>

                                <option
                                    v-for="state in stateOptions"
                                    :key="state.id"
                                    :value="state.id"
                                >
                                    {{ state.name }}
                                </option>
                            </select>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div v-else class="text-center py-5 text-muted">
            <div v-if="loading" class="spinner-border"></div>
            <div v-else>No orders found</div>
        </div>
    </div>
</template>