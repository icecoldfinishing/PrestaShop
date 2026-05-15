<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { psGet } from '../../../utils/prestashop-api';
import { getXmlText } from '../../../utils/products/product-api';
import { loggedCustomer } from '../../../utils/auth/auth-state';

type OrderRow = {
  id: string;
  total_paid: number;
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
  if (Array.isArray(name)) return getXmlText(name[0]);
  return getXmlText(name);
};

const getStateLabel = (stateId: string) => {
  const state = orderStates.value.find((s) => s.id === String(stateId));
  return state?.name || 'Inconnu';
};

const loadStates = async () => {
  const data = await psGet('order_states', '', { display: 'full' });

  const raw = data?.prestashop?.order_states?.order_state;
  const list = Array.isArray(raw) ? raw : raw ? [raw] : [];

  orderStates.value = list.map((s: any) => ({
    id: String(s.id),
    name: getStateName(s),
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
      sort: '[id_DESC]'
    });

    const raw = data?.prestashop?.orders?.order;
    const list = Array.isArray(raw) ? raw : raw ? [raw] : [];

    orders.value = list.map((o: any) => ({
      id: String(o.id),
      total_paid: parseFloat(o.total_paid || 0),
      current_state: getXmlText(o.current_state),
      date_add: new Date(getXmlText(o.date_add)).toLocaleString(),
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

  <!-- HEADER -->
  <div class="mb-4">
    <h2 class="fw-bold">
      <i class="bi bi-bag-check me-2"></i>
      Mes commandes
    </h2>
    <p class="text-muted mb-0">Historique de vos achats</p>
  </div>

  <!-- LOADING -->
  <div v-if="loading" class="text-center py-5">
    <div class="spinner-border text-dark"></div>
    <p class="text-muted mt-2">Chargement...</p>
  </div>

  <!-- EMPTY -->
  <div v-else-if="orders.length === 0" class="text-center py-5 bg-light rounded-4">
    <i class="bi bi-inbox display-4 text-muted"></i>
    <p class="mt-3 text-muted">Aucune commande</p>
  </div>

  <!-- LIST -->
  <div v-else class="row g-4">

    <div class="col-md-6 col-lg-4" v-for="order in orders" :key="order.id">

      <div class="card order-card border-0 shadow-sm h-100">

        <div class="card-body">

          <!-- HEADER CARD -->
          <div class="d-flex justify-content-between align-items-start mb-3">

            <div>
              <h5 class="fw-bold mb-1">
                Commande #{{ order.id }}
              </h5>

              <small class="text-muted">
                {{ order.date_add }}
              </small>
            </div>

            <span class="badge bg-secondary">
              {{ getStateLabel(order.current_state) }}
            </span>

          </div>

          <!-- PRICE -->
          <div class="price-box mb-2">
            {{ order.total_paid.toFixed(2) }} €
          </div>

          <!-- SIMPLE INFO -->
          <div class="text-muted small">
            Paiement enregistré
          </div>

        </div>

      </div>

    </div>

  </div>

</div>
</template>
<style scoped>
.order-card {
  border-radius: 18px;
  transition: 0.25s ease;
}

.order-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.price-box {
  font-size: 1.3rem;
  font-weight: 700;
  color: #ff6b00;
}
</style>