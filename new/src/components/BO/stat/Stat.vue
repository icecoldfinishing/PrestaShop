<script setup>
import { ref, onMounted, computed } from 'vue'
import { psGet } from '../../../utils/prestashop-api'

const orders = ref([])
const orderDetails = ref([])
const products = ref([])
const categories = ref([])
const stocks = ref([])
const loading = ref(false)

/**
 * HELPER FUNCTIONS FOR CLEANING PRESTASHOP API XML DATA
 */
const cleanId = (idField) => {
  if (idField === null || idField === undefined || idField === '') return '';
  if (typeof idField === 'object') {
    return String(idField['#text'] ?? idField['@_id'] ?? '').trim();
  }
  return String(idField).trim();
};

const getXmlText = (value) => {
  if (value === null || value === undefined) return '';
  if (typeof value === 'object' && '#text' in value) {
    return String(value['#text']).trim();
  }
  return String(value).trim();
};

const extractText = (field) => {
  if (!field) return '';
  const target = field.language ? field.language : field;
  return getXmlText(target);
};

/**
 * LOAD DATA
 */
const load = async () => {
  loading.value = true
  try {
    const [ordersRes, detailsRes, productsRes, categoriesRes, stocksRes] = await Promise.all([
      psGet('orders', '', { display: '[id,current_state,total_paid_tax_excl]' }),
      psGet('order_details', '', { display: '[id_order,product_id,product_attribute_id,product_quantity,unit_price_tax_excl]' }),
      psGet('products', '', { display: '[id,id_category_default,wholesale_price,name]' }),
      psGet('categories', '', { display: '[id,name]' }),
      psGet('stock_availables', '', { display: 'full' })
    ])

    // Correctly normalize and clean order data
    orders.value = [].concat(ordersRes?.prestashop?.orders?.order || []).map(o => ({
      id: cleanId(o.id),
      current_state: cleanId(o.current_state),
      total_paid_tax_excl: parseFloat(getXmlText(o.total_paid_tax_excl) || 0)
    }))

    // Correctly normalize and clean order details data
    orderDetails.value = [].concat(detailsRes?.prestashop?.order_details?.order_detail || []).map(d => ({
      id_order: cleanId(d.id_order),
      product_id: cleanId(d.product_id),
      product_attribute_id: cleanId(d.product_attribute_id) || '0',
      product_quantity: parseInt(getXmlText(d.product_quantity) || 0),
      unit_price_tax_excl: parseFloat(getXmlText(d.unit_price_tax_excl) || 0)
    }))

    // Correctly normalize and clean products data
    products.value = [].concat(productsRes?.prestashop?.products?.product || []).map(p => ({
      id: cleanId(p.id),
      id_category_default: cleanId(p.id_category_default),
      wholesale_price: parseFloat(getXmlText(p.wholesale_price) || 0),
      name: extractText(p.name)
    }))

    // Correctly normalize and clean categories data
    categories.value = [].concat(categoriesRes?.prestashop?.categories?.category || []).map(c => ({
      id: cleanId(c.id),
      name: extractText(c.name)
    }))

    // Correctly normalize and clean stocks data
    stocks.value = [].concat(stocksRes?.prestashop?.stock_availables?.stock_available || []).map(s => ({
      id: cleanId(s.id),
      id_product: cleanId(s.id_product),
      id_product_attribute: cleanId(s.id_product_attribute) || '0',
      quantity: parseInt(getXmlText(s.quantity) || 0)
    }))

  } catch (e) {
    console.error('Erreur stats:', e)
  } finally {
    loading.value = false
  }
}

/**
 * FINANCIAL VALID ORDERS
 * Excludes Cancelled (6) and Refunded (7)
 */
const validOrders = computed(() => {
  return orders.value.filter(o => !['6', '7'].includes(String(o.current_state)))
})

/**
 * STOCK ACTIVE ORDERS (PENDING SHIPMENT)
 * Excludes Shipped (4), Delivered (5), Cancelled (6) and Refunded (7)
 */
const activeOrders = computed(() => {
  return orders.value.filter(o => !['4', '5', '6', '7'].includes(String(o.current_state)))
})

const activeOrderIds = computed(() => {
  return new Set(activeOrders.value.map(o => o.id))
})

/**
 * DYNAMIC MAP FOR RESERVED STOCKS
 */
const reservedMap = computed(() => {
  const map = {}
  orderDetails.value.forEach(od => {
    if (activeOrderIds.value.has(od.id_order)) {
      const pId = od.product_id
      const qty = od.product_quantity
      map[pId] = (map[pId] || 0) + qty
    }
  })
  return map
})

/**
 * TOTAL SALES HT (Tax excl.)
 */
const totalSalesHT = computed(() => {
  return validOrders.value.reduce((sum, o) => {
    return sum + parseFloat(o.total_paid_tax_excl || 0)
  }, 0)
})

/**
 * PRODUCT LOOKUP MAP
 */
const productMap = computed(() => {
  const map = {}
  products.value.forEach(p => {
    map[p.id] = {
      category: p.id_category_default,
      cost: p.wholesale_price
    }
  })
  return map
})

/**
 * TOTAL PURCHASE HT (based on wholesale_price * quantity sold of valid orders)
 */
const totalPurchaseHT = computed(() => {
  const validOrderIds = new Set(validOrders.value.map(o => o.id))
  return orderDetails.value.reduce((sum, d) => {
    if (!validOrderIds.has(d.id_order)) return sum

    const product = productMap.value[d.product_id]
    if (!product) return sum

    const qty = d.product_quantity
    return sum + (product.cost * qty)
  }, 0)
})

/**
 * GLOBAL PROFIT
 */
const totalProfit = computed(() => {
  return totalSalesHT.value - totalPurchaseHT.value
})

/**
 * CATEGORY LOOKUP MAP
 */
const categoryMap = computed(() => {
  const map = {}
  categories.value.forEach(c => {
    map[c.id] = c.name
  })
  return map
})

/**
 * PROFIT BY CATEGORY
 */
const profitByCategory = computed(() => {
  const result = {}
  const validOrderIds = new Set(validOrders.value.map(o => o.id))

  orderDetails.value.forEach(d => {
    if (!validOrderIds.has(d.id_order)) return

    const product = productMap.value[d.product_id]
    if (!product) return

    const qty = d.product_quantity
    const sale = d.unit_price_tax_excl * qty
    const cost = product.cost * qty
    const catId = product.category || 'unknown'
    const catName = categoryMap.value[catId] || (catId === 'unknown' ? 'Autre' : `Catégorie ${catId}`)

    if (!result[catId]) {
      result[catId] = { name: catName, sales: 0, cost: 0, profit: 0 }
    }

    result[catId].sales += sale
    result[catId].cost += cost
    result[catId].profit += (sale - cost)
  })

  return result
})

/**
 * STOCKS BY CATEGORY
 * Columns: Catégorie, Qté physique, Qté reservé, Qté disponible
 */
const stockByCategory = computed(() => {
  const result = {}

  // Pre-initialize standard categories
  categories.value.forEach(c => {
    if (c.id === '1' || c.id === '2') return // Skip Root & Home meta categories
    result[c.id] = {
      name: c.name,
      physical: 0,
      reserved: 0,
      available: 0
    }
  })

  const unknownKey = 'unknown'

  products.value.forEach(p => {
    const catId = p.id_category_default || unknownKey
    
    // Find main stock level for this product
    const mainStock = stocks.value.find(s => s.id_product === p.id && s.id_product_attribute === '0')
    const available = mainStock ? mainStock.quantity : 0
    const reserved = reservedMap.value[p.id] || 0
    const physical = available + reserved

    if (!result[catId]) {
      result[catId] = {
        name: catId === unknownKey ? 'Autre' : `Catégorie ${catId}`,
        physical: 0,
        reserved: 0,
        available: 0
      }
    }

    result[catId].physical += physical
    result[catId].reserved += reserved
    result[catId].available += available
  })

  // Only return categories that have active products or stock levels to keep it focused
  return Object.values(result).filter(c => c.physical > 0 || c.reserved > 0 || c.available > 0)
})

onMounted(load)
</script>

<template>
  <div class="container py-4">

    <!-- Header Section -->
    <div class="stats-header d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Statistiques Globales</h2>
        <p class="text-white-50 mb-0 small">Suivi en temps réel des ventes, coûts d'achats, bénéfices et stocks par catégorie.</p>
      </div>
      <button class="btn btn-light btn-sm fw-semibold shadow-xs" @click="load" :disabled="loading">
        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
        <i v-else class="bi bi-arrow-clockwise me-1"></i> Actualiser
      </button>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
      <p class="mt-3 text-muted fw-semibold">Analyse et chargement des données...</p>
    </div>

    <div v-else>

      <!-- KPI Grid -->
      <div class="metrics-grid mb-4">
        
        <!-- Ventes HT Card -->
        <div class="metric-card">
          <div class="metric-icon-wrapper icon-sales">
            <i class="bi bi-cash-stack"></i>
          </div>
          <div class="metric-info">
            <h5>Ventes HT</h5>
            <h3>{{ totalSalesHT.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} €</h3>
          </div>
        </div>

        <!-- Achats HT Card -->
        <div class="metric-card">
          <div class="metric-icon-wrapper icon-purchase">
            <i class="bi bi-basket3-fill"></i>
          </div>
          <div class="metric-info">
            <h5>Achats HT</h5>
            <h3>{{ totalPurchaseHT.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} €</h3>
          </div>
        </div>

        <!-- Bénéfice Card -->
        <div class="metric-card">
          <div class="metric-icon-wrapper" :class="totalProfit >= 0 ? 'icon-profit' : 'icon-profit-negative'">
            <i class="bi bi-piggy-bank-fill"></i>
          </div>
          <div class="metric-info">
            <h5>Bénéfice Net</h5>
            <h3 :class="totalProfit >= 0 ? 'text-success' : 'text-danger'">
              {{ totalProfit.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} €
            </h3>
          </div>
        </div>

      </div>

      <!-- Main Tables Grid -->
      <div class="row g-4">

        <!-- Financial Table -->
        <div class="col-lg-6">
          <div class="section-card h-100">
            <h4 class="fw-bold mb-3 text-dark d-flex align-items-center">
              <i class="bi bi-pie-chart-fill me-2 text-primary"></i> Bénéfice par catégorie
            </h4>
            <div class="table-responsive">
              <table class="table table-custom align-middle">
                <thead>
                  <tr>
                    <th>Catégorie</th>
                    <th class="text-end">Ventes</th>
                    <th class="text-end">Achats</th>
                    <th class="text-end">Bénéfice</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="Object.keys(profitByCategory).length === 0">
                    <td colspan="4" class="text-center py-4 text-muted small">Aucune vente enregistrée.</td>
                  </tr>
                  <tr v-for="(c, id) in profitByCategory" :key="id">
                    <td class="fw-semibold text-dark">{{ c.name }}</td>
                    <td class="text-end">{{ c.sales.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} €</td>
                    <td class="text-end">{{ c.cost.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} €</td>
                    <td class="text-end">
                      <span class="badge badge-profit" :class="c.profit >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'">
                        {{ c.profit >= 0 ? '+' : '' }}{{ c.profit.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} €
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Stock levels Table (New requirement matching mockup) -->
        <div class="col-lg-6">
          <div class="section-card h-100">
            <h4 class="fw-bold mb-3 text-dark d-flex align-items-center">
              <i class="bi bi-box-seam-fill me-2 text-warning"></i> Niveaux de Stock par catégorie
            </h4>
            <div class="table-responsive">
              <table class="table table-custom align-middle">
                <thead>
                  <tr>
                    <th>Catégorie</th>
                    <th class="text-center">Qté physique</th>
                    <th class="text-center">Qté réservée</th>
                    <th class="text-center">Qté disponible</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="stockByCategory.length === 0">
                    <td colspan="4" class="text-center py-4 text-muted small">Aucun produit ou stock configuré.</td>
                  </tr>
                  <tr v-for="c in stockByCategory" :key="c.name">
                    <td class="fw-semibold text-dark">{{ c.name }}</td>
                    <td class="text-center">
                      <span class="badge stock-badge bg-secondary-subtle text-secondary fw-bold px-3">
                        {{ c.physical }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge stock-badge" :class="c.reserved > 0 ? 'bg-info-subtle text-info fw-bold px-3' : 'bg-light text-muted px-3'">
                        {{ c.reserved }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span class="badge stock-badge fw-bold px-3" :class="c.available > 5 ? 'bg-success-subtle text-success' : (c.available > 0 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger')">
                        {{ c.available }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</template>

<style scoped>
.stats-header {
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  border-radius: 12px;
  padding: 1.5rem 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
}

.metric-card {
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.06);
  border-radius: 16px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.25rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.metric-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.metric-icon-wrapper {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

.icon-sales {
  background-color: rgba(13, 110, 253, 0.1);
  color: #0d6efd;
}

.icon-purchase {
  background-color: rgba(253, 126, 20, 0.1);
  color: #fd7e14;
}

.icon-profit {
  background-color: rgba(25, 135, 84, 0.1);
  color: #198754;
}

.icon-profit-negative {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
}

.metric-info h5 {
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 600;
}

.metric-info h3 {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0;
  color: #212529;
}

.section-card {
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.06);
  border-radius: 16px;
  padding: 1.75rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.table-custom {
  margin-bottom: 0;
}

.table-custom th {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
  color: #6c757d;
  border-bottom-width: 2px;
  padding: 1rem 0.75rem;
}

.table-custom td {
  padding: 1rem 0.75rem;
  vertical-align: middle;
}

.table-custom tbody tr {
  transition: background-color 0.15s ease;
}

.table-custom tbody tr:hover {
  background-color: rgba(13, 110, 253, 0.02) !important;
}

.badge-profit {
  font-size: 0.85rem;
  padding: 0.4em 0.8em;
  font-weight: 600;
  border-radius: 6px;
}

.stock-badge {
  font-size: 0.85rem;
  padding: 0.35em 0.7em;
  border-radius: 6px;
}

.shadow-xs {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
</style>