<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { psGet, PS_PUBLIC_ORIGIN } from '../../../utils/prestashop-api';
import { getXmlText, cleanId } from '../../../utils/products/product-api';
import { loggedCustomer } from '../../../utils/auth/auth-state';

type OrderRow = {
  id: string;
  total_paid: number;
  current_state: string;
  date_add: string;
  items: { id: string; name: string; quantity: string; imageId: string | null }[];
};

type OrderState = {
  id: string;
  name: string;
};

const orders = ref<OrderRow[]>([]);
const orderStates = ref<OrderState[]>([]);
const loading = ref(false);

const getImageUrl = (productId: string, imageId: string | null) => {
  if (!productId || !imageId) return null;
  return `${PS_PUBLIC_ORIGIN}/api/images/products/${productId}/${imageId}`;
};

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
      display: 'full',
      'filter[id_customer]': `[${loggedCustomer.value.id}]`,
      sort: '[id_DESC]'
    });

    const raw = data?.prestashop?.orders?.order;
    const list = Array.isArray(raw) ? raw : raw ? [raw] : [];

    const parsedOrders = await Promise.all(list.map(async (o: any) => {
      const rowsRaw = [].concat(o.associations?.order_rows?.order_row || []).filter(Boolean);
      
      const items = await Promise.all(rowsRaw.map(async (row: any) => {
        const pid = cleanId(row.product_id);
        
        // Fetch product to get its image if needed, or we can try to optimize
        // For now, let's just get the product associations for images
        let imageId = null;
          const pRes = await psGet('products', pid);
          const images = [].concat(pRes?.prestashop?.product?.associations?.images?.image || []).filter(Boolean);
          if (images.length > 0) {
            imageId = cleanId(images[0].id);
          }
          
        return {
          id: pid,
          name: getXmlText(row.product_name),
          quantity: getXmlText(row.product_quantity),
          imageId
        };
      }));

      return {
        id: String(o.id),
        total_paid: parseFloat(o.total_paid || 0),
        current_state: getXmlText(o.current_state),
        date_add: new Date(getXmlText(o.date_add)).toLocaleString('fr-FR', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }),
        items
      };
    }));

    orders.value = parsedOrders;
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
  <div class="mb-5">
    <h2 class="fw-bold display-6">
      <i class="bi bi-bag-heart text-primary me-2"></i>
      Mes commandes
    </h2>
    <p class="text-muted mb-0">Retrouvez ici le détail de vos achats et le suivi de vos colis.</p>
  </div>

  <!-- LOADING -->
  <div v-if="loading" class="text-center py-5">
    <div class="spinner-grow text-primary" role="status"></div>
    <p class="text-muted mt-3 fw-medium">Récupération de vos commandes...</p>
  </div>

  <!-- EMPTY -->
  <div v-else-if="orders.length === 0" class="text-center py-5 bg-white border rounded-4 shadow-sm">
    <div class="mb-3">
        <i class="bi bi-cart-x display-1 text-light"></i>
    </div>
    <h4 class="fw-bold">Aucune commande pour le moment</h4>
    <p class="text-muted">Vos futurs achats apparaîtront ici.</p>
    <button class="btn btn-primary rounded-pill px-4 mt-2" @click="$emit('continueShopping')">
        Commencer mes achats
    </button>
  </div>

  <!-- LIST -->
  <div v-else class="row g-4">

    <div class="col-12" v-for="order in orders" :key="order.id">

      <div class="card order-card border-0 shadow-sm overflow-hidden">
        
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-light text-dark border mb-2">#{{ order.id }}</span>
                    <h5 class="fw-bold mb-0">Commande passée le {{ order.date_add }}</h5>
                </div>
                <div class="text-end">
                    <span class="badge rounded-pill px-3 py-2" :class="order.current_state == '10' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary'">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ getStateLabel(order.current_state) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
          <div class="row align-items-center">
            
            <!-- IMAGES SUMMARY -->
            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-2 mb-3 mb-md-0">
                    <div v-for="(item, idx) in order.items.slice(0, 4)" :key="idx" class="product-thumb-container shadow-sm border rounded-3 overflow-hidden" :title="item.name">
                        <img v-if="item.imageId" :src="getImageUrl(item.id, item.imageId)" :alt="item.name" class="product-thumb" />
                        <div v-else class="thumb-fallback">
                            <i class="bi bi-image"></i>
                        </div>
                        <span v-if="item.quantity != '1'" class="qty-badge">{{ item.quantity }}</span>
                    </div>
                    <div v-if="order.items.length > 4" class="more-items border rounded-3 d-flex align-items-center justify-content-center text-muted small bg-light">
                        +{{ order.items.length - 4 }}
                    </div>
                </div>
            </div>

            <!-- PRICE & ACTION -->
            <div class="col-md-5 text-md-end border-start-md">
                <div class="mb-3">
                    <span class="text-muted small d-block">Montant total</span>
                    <span class="display-6 fw-bold text-dark">{{ order.total_paid.toFixed(2) }} €</span>
                </div>
                <button class="btn btn-outline-dark rounded-pill px-4 btn-sm">
                    Détails de la commande
                </button>
            </div>

          </div>
        </div>

      </div>

    </div>

  </div>

</div>
</template>

<style scoped>
.order-card {
  border-radius: 20px;
  transition: all 0.3s ease;
  border: 1px solid rgba(0,0,0,0.05) !important;
}

.order-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.product-thumb-container {
    width: 70px;
    height: 70px;
    position: relative;
    background: #fff;
}

.product-thumb {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 5px;
}

.thumb-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #dee2e6;
}

.more-items {
    width: 70px;
    height: 70px;
}

.qty-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #000;
    color: #fff;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
    border: 2px solid #fff;
}

@media (min-width: 768px) {
    .border-start-md {
        border-left: 1px solid #eee !important;
    }
}
</style>
