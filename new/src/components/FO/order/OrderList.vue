<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { psGet } from '../../../utils/prestashop-api';
import { getXmlText } from '../../../utils/products/product-api';
import { loggedCustomer } from '../../../utils/auth/auth-state';

type OrderRow = {
  id: string;
  total_paid: string;
  current_state: string;
  date_add: string;
};

type OrderState = {
  id: string;
  name: string;
};

const orders = ref<OrderRow[]>([]);
const orderStates = ref<OrderState[]>([]);
const loading = ref(false);

const getStateName = (state: any) => {
  const name = state?.name?.language;
  if (Array.isArray(name)) {
    return getXmlText(name[0]);
  }
  return getXmlText(name);
};

const getStateLabel = (stateId: string) => {
  const state = orderStates.value.find((s) => s.id === String(stateId));
  return state ? state.name : 'Inconnu';
};

const loadStates = async () => {
  const data = await psGet('order_states', '', { display: 'full' });
  const raw = data?.prestashop?.order_states?.order_state;
  if (!raw) {
    orderStates.value = [];
    return;
  }
  const list = Array.isArray(raw) ? raw : [raw];
  orderStates.value = list.map((state: any) => ({
    id: String(state.id),
    name: getStateName(state),
  }));
};

const loadOrders = async () => {
  if (!loggedCustomer.value?.id) {
    orders.value = [];
    return;
  }

  loading.value = true;
  try {
    const data = await psGet('orders', '', {
      display: '[id,total_paid,current_state,date_add]',
      'filter[id_customer]': `[${loggedCustomer.value.id}]`,
    });

    const raw = data?.prestashop?.orders?.order;
    if (!raw) {
      orders.value = [];
      return;
    }

    const list = Array.isArray(raw) ? raw : [raw];
    orders.value = list.map((o: any) => ({
      id: String(o.id),
      total_paid: Number.parseFloat(o.total_paid || 0).toFixed(2),
      current_state: getXmlText(o.current_state),
      date_add: getXmlText(o.date_add),
    }));
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  await loadStates();
  await loadOrders();
});
</script>

<template>
  <div class="container py-4">
    <h2 class="fw-bold mb-4">Mes commandes</h2>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border"></div>
    </div>

    <div v-else-if="orders.length" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div class="col" v-for="order in orders" :key="order.id">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <h5 class="fw-bold">Commande #{{ order.id }}</h5>
            <p class="mb-1">Statut: {{ getStateLabel(order.current_state) }}</p>
            <p class="text-primary fw-bold">{{ order.total_paid }} €</p>
            <p class="text-muted small">{{ order.date_add }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-5 text-muted">Aucune commande.</div>
  </div>
</template>
