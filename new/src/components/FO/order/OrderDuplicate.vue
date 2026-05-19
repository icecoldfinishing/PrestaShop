<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { psGet, PS_PUBLIC_ORIGIN } from '../../../utils/prestashop-api';

import {
  cleanId,
  getXmlText,
  psCreateCart,
  psCreateOrder,
  psEnsureCustomerAddress,
  psGetCartSecureKey,
  psUpdateOrderStateCustom,
} from '../../../utils/products/product-api';
import { loggedCustomer } from '../../../utils/auth/auth-state';

type DuplicateItem = {
  productId: string;
  attributeId: string;
  name: string;
  reference: string;
  unitQty: number;
  imageId: string | null;
  stockAvailable: number;
};

const props = defineProps<{ orderId: string | null; count: number }>();
const emit = defineEmits<{ (e: 'back'): void }>();

const loading = ref(false);
const processing = ref(false);
const duplicateCount = ref(1);
const items = ref<DuplicateItem[]>([]);
const logs = ref<string[]>([]);
const errorMsg = ref('');

const getImageUrl = (productId: string, imageId: string | null) => {
  if (!productId || !imageId) return null;
  return `${PS_PUBLIC_ORIGIN}/api/images/products/${productId}/${imageId}`;
};

const addLog = (msg: string) => {
  const time = new Date().toLocaleTimeString();
  logs.value.unshift(`[${time}] ${msg}`);
};

const displayItems = computed(() =>
  items.value.map((item) => {
    const required = item.unitQty * duplicateCount.value;
    const missing = Math.max(0, required - item.stockAvailable);
    return {
      ...item,
      required,
      missing,
    };
  })
);

const hasMissing = computed(() => displayItems.value.some((i) => i.missing > 0));

const loadOrder = async () => {
  if (!props.orderId) return;

  loading.value = true;
  errorMsg.value = '';
  items.value = [];

  try {
    const data = await psGet('orders', props.orderId, { display: 'full' });
    const rawRows = [].concat(data?.prestashop?.order?.associations?.order_rows?.order_row || []).filter(Boolean);

    const parsedItems = await Promise.all(rawRows.map(async (row: any) => {
      const productId = cleanId(row.product_id);
      const attributeId = cleanId(row.product_attribute_id) || '0';
      const unitQty = Math.max(1, parseInt(getXmlText(row.product_quantity) || '1', 10));

      const pRes = await psGet('products', productId);
      const images = [].concat(pRes?.prestashop?.product?.associations?.images?.image || []).filter(Boolean);
      const imageId = images.length > 0 ? cleanId(images[0].id) : null;

      const stockRes = await psGet('stock_availables', '', {
        'filter[id_product]': `[${productId}]`,
        display: 'full',
      });
      const rawStocks = stockRes?.prestashop?.stock_availables?.stock_available;
      const stockArr = rawStocks ? (Array.isArray(rawStocks) ? rawStocks : [rawStocks]) : [];
      const match = stockArr.find((s: any) => (cleanId(s.id_product_attribute) || '0') === attributeId);
      const stockAvailable = parseInt(getXmlText(match?.quantity) || '0', 10) || 0;

      return {
        productId,
        attributeId,
        name: getXmlText(row.product_name) || 'Produit',
        reference: getXmlText(row.product_reference) || '',
        unitQty,
        imageId,
        stockAvailable,
      };
    }));

    items.value = parsedItems;
  } catch (e: any) {
    errorMsg.value = e?.message || 'Erreur lors du chargement de la commande.';
  } finally {
    loading.value = false;
  }
};

const handleDuplicate = async () => {
  if (!loggedCustomer.value?.id) {
    errorMsg.value = 'Veuillez vous connecter pour dupliquer la commande.';
    return;
  }
  if (!items.value.length) {
    errorMsg.value = 'Aucun article a dupliquer.';
    return;
  }
  if (hasMissing.value) {
    errorMsg.value = 'Stock insuffisant pour la duplication demandee.';
    return;
  }

  processing.value = true;
  errorMsg.value = '';
  logs.value = [];

  try {
    const addressId = await psEnsureCustomerAddress(loggedCustomer.value);
    if (!addressId) throw new Error('Adresse client introuvable.');

    const itemsForCart = items.value.map((item) => ({
      id: item.productId,
      id_attribute: Number(item.attributeId) || 0,
      quantity: item.unitQty,
    }));

    for (let i = 0; i < duplicateCount.value; i += 1) {
      addLog(`Duplication ${i + 1}/${duplicateCount.value}...`);
      const cartId = await psCreateCart(loggedCustomer.value.id, itemsForCart, addressId);
      const secureKey = await psGetCartSecureKey(cartId);

      const orderId = await psCreateOrder({
        cartId,
        customerId: loggedCustomer.value.id,
        addressId,
        secureKey,
      });

      await psUpdateOrderStateCustom(orderId, 5);
      addLog(`Commande #${orderId} creee.`);
    }

    addLog('Duplication terminee.');
  } catch (e: any) {
    errorMsg.value = e?.message || 'Erreur lors de la duplication.';
  } finally {
    processing.value = false;
  }
};

watch(
  () => props.count,
  (next) => {
    const val = Math.max(1, Number(next) || 1);
    duplicateCount.value = val;
  },
  { immediate: true }
);

watch(
  () => props.orderId,
  () => {
    loadOrder();
  }
);

onMounted(loadOrder);
</script>

<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
      <div>
        <h2 class="fw-bold mb-1">Duplication de commande</h2>
        <p class="text-muted mb-0">Commande #{{ orderId || '-' }}</p>
      </div>
      <button class="btn btn-outline-dark rounded-pill px-3" @click="emit('back')">
        Retour aux commandes
      </button>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border"></div>
      <p class="text-muted mt-3">Chargement des articles...</p>
    </div>

    <div v-else>
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 flex-wrap">
            <div>
              <label class="small text-muted">Nombre de duplications</label>
              <input
                v-model.number="duplicateCount"
                type="number"
                min="1"
                class="form-control form-control-sm"
                style="max-width: 140px;"
              />
            </div>
            <div class="small text-muted">
              Total de commandes a creer : <b>{{ duplicateCount }}</b>
            </div>
          </div>
        </div>
      </div>

      <div v-if="errorMsg" class="alert alert-danger py-2">{{ errorMsg }}</div>

      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Produit</th>
                  <th class="text-center">Qty/commande</th>
                  <th class="text-center">Total demande</th>
                  <th class="text-center">Stock dispo</th>
                  <th class="text-center">Manquant</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in displayItems" :key="item.productId + '-' + item.attributeId">
                  <td class="d-flex align-items-center gap-2">
                    <div class="thumb">
                      <img
                        v-if="item.imageId"
                        :src="getImageUrl(item.productId, item.imageId)"
                        :alt="item.name"
                      />
                      <div v-else class="thumb-fallback">
                        <i class="bi bi-image"></i>
                      </div>
                    </div>
                    <div>
                      <div class="fw-semibold">{{ item.name }}</div>
                      <div class="text-muted small">Ref: {{ item.reference || 'N/A' }}</div>
                    </div>
                  </td>
                  <td class="text-center">{{ item.unitQty }}</td>
                  <td class="text-center">{{ item.required }}</td>
                  <td class="text-center">{{ item.stockAvailable }}</td>
                  <td class="text-center">
                    <span :class="item.missing > 0 ? 'text-danger fw-semibold' : 'text-success'">
                      {{ item.missing }}
                    </span>
                  </td>
                </tr>
                <tr v-if="!displayItems.length">
                  <td colspan="5" class="text-center text-muted py-4">Aucun article.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="small" :class="hasMissing ? 'text-danger' : 'text-success'">
          <i class="bi" :class="hasMissing ? 'bi-x-circle' : 'bi-check-circle'"></i>
          <span v-if="hasMissing">Stock insuffisant pour certaines lignes.</span>
          <span v-else>Stock suffisant pour la duplication.</span>
        </div>
        <button class="btn btn-primary rounded-pill px-4" :disabled="processing || hasMissing" @click="handleDuplicate">
          <span v-if="!processing">Valider la duplication</span>
          <span v-else>
            <span class="spinner-border spinner-border-sm me-2"></span>
            Traitement...
          </span>
        </button>
      </div>

      <div v-if="logs.length" class="mt-4 p-3 rounded bg-dark text-light" style="font-size:12px;">
        <div v-for="(line, idx) in logs" :key="idx">{{ line }}</div>
      </div>
    </div>
  </div>
</template>

