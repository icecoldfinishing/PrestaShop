<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { getXmlText, psGet, psUpdateOrderState, psEnsureCustomerAddress, psGetCartSecureKey, psCreateOrder, psLoadCartItems } from '../../../utils/prestashop-api';

/* ─────────────────────────────────────────
   LOG VISIBLE dans l'UI (20 derniers msgs)
───────────────────────────────────────── */
const logs = ref([]);
const addLog = (msg) => {
    const line = `[${new Date().toLocaleTimeString()}] ${msg}`;
    console.log('[OrderList]', line);
    logs.value.unshift(line);
    if (logs.value.length > 20) logs.value.pop();
};

/* ─────────────────────────────────────────
   STATE
───────────────────────────────────────── */
const orders        = ref([]);
const carts         = ref([]);
const orderStates   = ref([]);
const loading       = ref(false);
const updatingId    = ref(null);
const orderStateMap = ref({});

/* ─────────────────────────────────────────
   SENTINEL "panier"
   "panier" n'est PAS un order_state PrestaShop.
   C'est un flag interne qui déclenche DELETE /orders/{id}.
───────────────────────────────────────── */
const CART_SENTINEL = '__cart__';
const isCartAction  = (v) => v === CART_SENTINEL;

/* ─────────────────────────────────────────
   DELETE commande via axios.delete
   ATTENTION : psGet() dans prestashop-api.js fait toujours
   axios.GET — il ne supporte pas DELETE.
   On appelle directement axios ici.
───────────────────────────────────────── */
const API_KEY  = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

const deleteOrder = async (orderId) => {
    addLog(`🗑️ DELETE ${BASE_URL}/orders/${orderId} …`);
    const res = await axios.delete(`${BASE_URL}/orders/${orderId}`, {
        params: { ws_key: API_KEY },
    });
    addLog(`✅ DELETE → HTTP ${res.status}`);
    return res;
};

/* ─────────────────────────────────────────
   HELPERS
───────────────────────────────────────── */
const normalize    = (v) => String(v || '').trim().toLowerCase();
const getStateName = (state) => {
    const name = state?.name?.language;
    return Array.isArray(name) ? getXmlText(name[0]) : getXmlText(name);
};

/* ─────────────────────────────────────────
   LOAD ORDERS
───────────────────────────────────────── */
const getAllOrders = async () => {
    const data = await psGet('orders', '', {
        display: '[id,id_customer,total_paid,current_state,date_add,id_cart]',
    });
    const list = data?.prestashop?.orders?.order;
    const arr  = list ? (Array.isArray(list) ? list : [list]) : [];

    orders.value = arr.map(o => {
        const id    = String(o.id);
        const state = getXmlText(o.current_state);
        orderStateMap.value[`order-${id}`] = state;
        return {
            id,
            type:          'order',
            id_cart:       getXmlText(o.id_cart),
            id_customer:   getXmlText(o.id_customer),
            total_paid:    parseFloat(o.total_paid || 0).toFixed(2),
            current_state: state,
            date_add:      getXmlText(o.date_add),
        };
    });
    addLog(`📦 ${orders.value.length} commande(s)`);
};

/* ─────────────────────────────────────────
   LOAD CARTS (exclut ceux liés à une commande)
───────────────────────────────────────── */
const getAllCarts = async () => {
    const data = await psGet('carts', '', { display: 'full' });
    const list  = data?.prestashop?.carts?.cart;
    const arr   = list ? (Array.isArray(list) ? list : [list]) : [];

    const orderCartIds = orders.value.map(o => String(o.id_cart)).filter(Boolean);

    const processed = await Promise.all(
        arr
            .filter(c => !orderCartIds.includes(String(c.id)))
            .map(async (c) => {
                const id = String(c.id);
                orderStateMap.value[`cart-${id}`] = CART_SENTINEL;

                let total = 0;
                try {
                    const items = await psLoadCartItems(id);
                    total = items.reduce((s, i) => s + i.price * i.quantity, 0);
                } catch (err) {
                    addLog(`⚠️ Items panier #${id}: ${err.message}`);
                }

                return {
                    id,
                    type:          'cart',
                    id_customer:   getXmlText(c.id_customer),
                    total_paid:    total.toFixed(2),
                    current_state: CART_SENTINEL,
                    date_add:      getXmlText(c.date_add),
                };
            })
    );

    carts.value = processed;
    addLog(`🛒 ${carts.value.length} panier(s) libre(s)`);
};

/* ─────────────────────────────────────────
   ORDER STATES
───────────────────────────────────────── */
const getAllOrderStates = async () => {
    const data = await psGet('order_states', '', { display: 'full' });
    const list = data?.prestashop?.order_states?.order_state;
    const arr  = list ? (Array.isArray(list) ? list : [list]) : [];
    orderStates.value = arr.map(s => ({ id: String(s.id), name: getStateName(s) }));
};

const findStateId = (keywords) =>
    orderStates.value.find(s =>
        keywords.some(k => normalize(s.name).includes(normalize(k)))
    )?.id || null;

/* IDs réels PrestaShop — strings numériques ou null */
const paidStateId     = computed(() => findStateId(['paiement accepté', 'payment accepted', 'paid']));
const canceledStateId = computed(() => findStateId(['annulé', 'cancel', 'canceled', 'annule']));

/* ─────────────────────────────────────────
   ACTION PRINCIPALE
───────────────────────────────────────── */
const setOrderState = async (id, type, stateId) => {
    if (!stateId) {
        addLog(`⚠️ stateId vide → ignoré (${type} #${id})`);
        return;
    }

    addLog(`▶️ action: type=${type} id=${id} stateId=${stateId}`);
    updatingId.value = `${type}-${id}`;

    try {

        /* ── A : PANIER → déjà panier ─────────────── */
        if (type === 'cart' && isCartAction(stateId)) {
            addLog('ℹ️ Déjà un panier, rien à faire');
            return;
        }

        /* ── B : PANIER → COMMANDE ────────────────── */
        if (type === 'cart' && !isCartAction(stateId)) {
            addLog(`📦 Panier #${id} → commande (état ${stateId})`);
            const cartObj = carts.value.find(c => c.id === id);
            if (!cartObj) { addLog(`❌ Panier #${id} introuvable`); return; }

            const addressId = await psEnsureCustomerAddress({ id: cartObj.id_customer });
            addLog(`📍 adresse=${addressId}`);

            const secureKey = await psGetCartSecureKey(id);
            const items     = await psLoadCartItems(id);
            addLog(`📥 ${items.length} article(s)`);

            const newOrderId = await psCreateOrder({
                cartId:     id,
                customerId: cartObj.id_customer,
                addressId:  addressId || 1,
                secureKey,
                lineItems:  items.map(i => ({
                    productId:          i.id,
                    productAttributeId: i.id_attribute ?? 0,
                    quantity:           i.quantity,
                    name:               i.name,
                    reference:          i.reference || '',
                    unitPriceTaxIncl:   Number(i.price) || 0,
                    unitPriceTaxExcl:   Number(i.price) || 0,
                })),
            });
            addLog(`✅ Commande #${newOrderId} créée`);
            await psUpdateOrderState(newOrderId, stateId);
            addLog(`🔁 État #${stateId} appliqué`);
        }

        /* ── C : COMMANDE → PANIER ─────────────────────
           1. Passer en "Annulé" → PS remet le stock
           2. DELETE la commande → libère le panier original
        ─────────────────────────────────────────────── */
        else if (type === 'order' && isCartAction(stateId)) {
            const order = orders.value.find(o => o.id === id);
            if (!order) { addLog(`❌ Commande #${id} introuvable`); return; }

            // Étape 1 : annuler pour déclencher le restockage PS
            const currentState = orderStateMap.value[`order-${id}`];
            if (canceledStateId.value && currentState !== canceledStateId.value) {
                addLog(`⏳ 1/2 — Annulation commande #${id} (état ${canceledStateId.value}) pour restockage…`);
                await psUpdateOrderState(id, canceledStateId.value);
                addLog(`✅ Annulée → stock remis à jour par PS`);
            } else {
                addLog(`ℹ️ Déjà annulée ou canceledStateId null — suppression directe`);
            }

            // Étape 2 : supprimer la commande → libère le panier
            addLog(`🗑️ 2/2 — DELETE commande #${id} → libère panier #${order.id_cart}`);
            await deleteOrder(id);

            orders.value = orders.value.filter(o => o.id !== id);
            delete orderStateMap.value[`order-${id}`];
            addLog(`♻️ Commande supprimée, panier #${order.id_cart} réapparaîtra au refresh`);
        }

        /* ── D : COMMANDE → autre état ───────────── */
        else if (type === 'order' && !isCartAction(stateId)) {
            addLog(`✏️ Commande #${id} → état #${stateId}`);
            await psUpdateOrderState(id, stateId);
            orderStateMap.value[`order-${id}`] = stateId;
            addLog(`✅ Statut mis à jour`);
        }

        await refresh();

    } catch (err) {
        addLog(`❌ ERREUR: ${err.message}`);
        console.error('[setOrderState]', err);
    } finally {
        updatingId.value = null;
    }
};

/* ─────────────────────────────────────────
   HELPERS UI
───────────────────────────────────────── */
const getActiveStateId = (id, type) => orderStateMap.value[`${type}-${id}`] || null;

const isPaid     = (id, type) => getActiveStateId(id, type) === paidStateId.value;
const isCanceled = (id, type) => getActiveStateId(id, type) === canceledStateId.value;
const isCart     = (id, type) => {
    const s = getActiveStateId(id, type);
    return !s || isCartAction(s) || type === 'cart';
};

const getStateLabel = (stateId) => {
    if (!stateId || isCartAction(stateId)) return 'Dans le panier';
    return orderStates.value.find(s => s.id === String(stateId))?.name || `État #${stateId}`;
};

/* ─────────────────────────────────────────
   LIST & REFRESH
───────────────────────────────────────── */
const allItems = computed(() => [...carts.value, ...orders.value]);

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
    addLog(`📋 ${orderStates.value.length} états PS: ${orderStates.value.map(s => `#${s.id}="${s.name}"`).join(' | ')}`);
    addLog(`💰 paidStateId=${paidStateId.value} | ❌ canceledStateId=${canceledStateId.value}`);
    await refresh();
});
</script>

<template>
    <div class="container py-4">

        <h2 class="fw-bold mb-3">Panier + Commandes</h2>

        <!-- ===== LOG PANEL ===== -->
        <div class="mb-4 p-3 rounded"
             style="background:#111;color:#0f0;font-family:monospace;font-size:11px;height:180px;overflow-y:auto;">
            <div v-if="!logs.length" style="color:#555">Logs apparaîtront ici…</div>
            <div v-for="(l, i) in logs" :key="i">{{ l }}</div>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border"></div>
        </div>

        <div v-else-if="allItems.length" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <div class="col" v-for="item in allItems" :key="item.type + item.id">

                <div class="card h-100 shadow-sm border-0"
                     :class="{ 'opacity-50': updatingId === `${item.type}-${item.id}` }">
                    <div class="card-body">

                        <h5 class="fw-bold">
                            {{ item.type === 'cart' ? '🛒 Cart' : '📦 Order' }} #{{ item.id }}
                        </h5>

                        <p class="text-muted mb-1">Client #{{ item.id_customer }}</p>

                        <p class="mb-1">
                            Statut : <strong>{{ getStateLabel(getActiveStateId(item.id, item.type)) }}</strong>
                        </p>

                        <p class="text-primary fw-bold">{{ item.total_paid }} €</p>
                        <p class="text-muted small">{{ item.date_add }}</p>

                        <!-- BOUTONS -->
                        <div class="d-flex gap-2 mt-3 flex-wrap">

                            <!--
                                Bouton "Panier" passe CART_SENTINEL (string constante).
                                Sur une commande → déclenche CAS C (DELETE).
                                Sur un panier   → désactivé (déjà panier).
                            -->
                            <button
                                class="btn btn-sm"
                                :class="isCart(item.id, item.type) ? 'btn-secondary' : 'btn-outline-secondary'"
                                :disabled="updatingId === `${item.type}-${item.id}` || isCart(item.id, item.type)"
                                :title="item.type === 'order' ? 'Supprime la commande et libère le panier' : 'Déjà un panier'"
                                @click="setOrderState(item.id, item.type, CART_SENTINEL)">
                                🛒 Panier
                            </button>

                            <button
                                class="btn btn-sm"
                                :class="isPaid(item.id, item.type) ? 'btn-success' : 'btn-outline-success'"
                                :disabled="updatingId === `${item.type}-${item.id}`"
                                @click="setOrderState(item.id, item.type, paidStateId)">
                                ✅ Payé
                            </button>

                            <button
                                class="btn btn-sm"
                                :class="isCanceled(item.id, item.type) ? 'btn-danger' : 'btn-outline-danger'"
                                :disabled="updatingId === `${item.type}-${item.id}`"
                                @click="setOrderState(item.id, item.type, canceledStateId)">
                                ❌ Annulé
                            </button>

                        </div>

                        <!-- DROPDOWN -->
                        <div class="mt-2">
                            <select
                                class="form-select form-select-sm"
                                :value="getActiveStateId(item.id, item.type)"
                                :disabled="updatingId === `${item.type}-${item.id}`"
                                @change="setOrderState(item.id, item.type, $event.target.value)">
                                <option value="">— Changer le statut —</option>
                                <option :value="CART_SENTINEL">🛒 Panier (supprime la commande)</option>
                                <option :value="paidStateId">✅ Paiement accepté</option>
                                <option :value="canceledStateId">❌ Annulé</option>
                            </select>
                        </div>

                        <!-- SPINNER -->
                        <div v-if="updatingId === `${item.type}-${item.id}`" class="mt-2 text-muted small">
                            <span class="spinner-border spinner-border-sm me-1"></span> Mise à jour…
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div v-else class="text-center text-muted py-5">
            Aucun panier / commande
        </div>

    </div>
</template>