<script setup>
import { ref, onMounted, computed } from 'vue';
import { psCount } from '../../../utils/prestashop-api';
import {  psGetOrdersDashboardStats, getXmlText } from '../../../utils/products/product-api';
import { psGet } from '../../../utils/prestashop-api';

const emit = defineEmits(['navigate']);

const stats = ref({
    products: 0,
    customers: 0,
    orders: 0,
});

const loading = ref(true);

const dashboardLoading = ref(true);
const dashboardError = ref('');
/** @type {import('vue').Ref<{ date: string, count: number, amount: number }[]>} */
const ordersByDay = ref([]);
/** @type {import('vue').Ref<{ count: number, amount: number }>} */
const ordersTotals = ref({ count: 0, amount: 0 });
const allOrders = ref([]);
const customersMap = ref({});
const statesMap = ref({});
const selectedDate = ref(''); // Vide = tous

const formatMoney = (n) => {
    const v = Number(n) || 0;
    return v.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const loadStats = async () => {
    loading.value = true;
    try {
        const [products, customers, orders] = await Promise.all([
            psCount('products'),
            psCount('customers'),
            psCount('orders'),
        ]);

        stats.value = {
            products,
            customers,
            orders,
        };
    } catch (error) {
        console.error('Error loading stats:', error);
    } finally {
        loading.value = false;
    }
};

const loadOrdersDashboard = async () => {
    dashboardLoading.value = true;
    dashboardError.value = '';
    try {
        const { byDay, totals, orders } = await psGetOrdersDashboardStats();
        ordersByDay.value = byDay;
        ordersTotals.value = totals;
        allOrders.value = orders;

        // Charger les noms des clients et les états
        const customerIds = [...new Set(orders.map(o => o.id_customer))].filter(Boolean);
        const stateIds = [...new Set(orders.map(o => o.current_state))].filter(Boolean);

        const [custData, stateData] = await Promise.all([
            psGet('customers', '', { display: '[id,firstname,lastname]', 'filter[id]': `[${customerIds.join('|')}]` }),
            psGet('order_states', '', { display: '[id,name]', 'filter[id]': `[${stateIds.join('|')}]` })
        ]);

        const rawCust = custData?.prestashop?.customers?.customer;
        const listCust = Array.isArray(rawCust) ? rawCust : rawCust ? [rawCust] : [];
        listCust.forEach(c => {
            customersMap.value[c.id] = `${getXmlText(c.firstname)} ${getXmlText(c.lastname)}`;
        });

        const rawStates = stateData?.prestashop?.order_states?.order_state;
        const listStates = Array.isArray(rawStates) ? rawStates : rawStates ? [rawStates] : [];
        listStates.forEach(s => {
            const name = s.name?.language;
            statesMap.value[s.id] = Array.isArray(name) ? getXmlText(name[0]) : getXmlText(name);
        });

    } catch (e) {
        console.error(e);
        dashboardError.value = 'Impossible de charger les statistiques commandes.';
        ordersByDay.value = [];
        ordersTotals.value = { count: 0, amount: 0 };
        allOrders.value = [];
    } finally {
        dashboardLoading.value = false;
    }
};

const getCustomerName = (id) => customersMap.value[id] || `Client #${id}`;
const getStateName = (id) => statesMap.value[id] || `Statut #${id}`;


const filteredOrders = computed(() => {
    if (!selectedDate.value) return allOrders.value;
    // On compare YYYY-MM-DD
    return allOrders.value.filter(o => o.date_add.startsWith(selectedDate.value));
});

// Grouper les commandes par date pour l'affichage
const groupedOrders = computed(() => {
    const groups = {};
    filteredOrders.value.forEach(order => {
        const date = order.date_add.slice(0, 10);
        if (!groups[date]) groups[date] = [];
        groups[date].push(order);
    });
    // Trier par date descendante
    return Object.keys(groups).sort((a, b) => b.localeCompare(a)).map(date => ({
        date,
        orders: groups[date]
    }));
});

onMounted(() => {
    loadStats();
    loadOrdersDashboard();
});
</script>

<template>
    <div class="container py-5">
        <!-- HEADER -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary">
                PrestaShop Manager
            </h1>
            <p class="lead text-muted mb-0">
                Gestion de la boutique et suivi des commandes
            </p>
        </div>

        <!-- STATS -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 hover-shadow stat-card products-card">
                    <div class="card-body text-center position-relative">
                        <div class="stat-icon mb-2">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="text-muted">Produits</h5>
                        <h2 class="fw-bold text-primary">{{ stats.products }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 hover-shadow stat-card customers-card">
                    <div class="card-body text-center position-relative">
                        <div class="stat-icon mb-2">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5 class="text-muted">Clients</h5>
                        <h2 class="fw-bold text-success">{{ stats.customers }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 hover-shadow stat-card orders-card">
                    <div class="card-body text-center position-relative">
                        <!-- PETITE CLOCHE DE NOTIF -->
                        <span v-if="stats.orders > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light p-2 mt-2 me-2">
                            <i class="bi bi-bell-fill"></i>
                        </span>
                        
                        <div class="stat-icon mb-2">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h5 class="text-muted">Commandes (total)</h5>
                        <h2 class="fw-bold text-warning">{{ stats.orders }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLEAU DE BORD COMMANDES -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Détail des commandes</h4>
                
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted fw-semibold">Filtrer par date :</label>
                    <div class="input-group input-group-sm" style="width: auto;">
                        <input v-model="selectedDate" type="date" class="form-control" />
                        <button v-if="selectedDate" class="btn btn-outline-secondary" @click="selectedDate = ''">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="dashboardLoading" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status" />
                    Chargement…
                </div>
                <div v-else-if="dashboardError" class="alert alert-warning mb-0">
                    {{ dashboardError }}
                </div>
                <template v-else>
                    
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Heure</th>
                                    <th scope="col">Client</th>
                                    <th scope="col" class="text-end">Montant</th>
                                    <th scope="col" class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="group in groupedOrders" :key="group.date">
                                    <!-- HEADER GROUPE DATE -->
                                    <tr class="table-secondary">
                                        <td colspan="5" class="fw-bold">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            {{ group.date }}
                                        </td>
                                    </tr>
                                    <!-- LIGNES DU GROUPE -->
                                    <tr v-for="order in group.orders" :key="order.id">
                                        <td>
                                            <span class="badge bg-light text-dark border">#{{ order.id }}</span>
                                        </td>
                                        <td class="small text-muted">{{ order.date_add.slice(11, 16) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ getCustomerName(order.id_customer).charAt(0) }}
                                                </div>
                                                <span class="fw-medium">{{ getCustomerName(order.id_customer) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-dark">{{ formatMoney(order.total_paid) }} €</td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill px-3 py-1 bg-success-subtle text-success border border-success-subtle">
                                                {{ getStateName(order.current_state) }}
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-if="!groupedOrders.length">
                                    <td colspan="5" class="text-muted text-center py-5">
                                        <i class="bi bi-search display-4 d-block mb-3 opacity-25"></i>
                                        Aucune commande trouvée pour cette période.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot v-if="!selectedDate && groupedOrders.length" class="table-group-divider">
                                <tr class="fw-bold">
                                    <td colspan="3">Cumul total</td>
                                    <td class="text-end text-primary">{{ formatMoney(ordersTotals.amount) }} €</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </template>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="mb-4">
            <h4 class="mb-3 fw-bold">Outils rapides</h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <div
                        class="card h-100 shadow-sm border-0 cursor-pointer"
                        @click="emit('navigate', 'csv-import')"
                    >
                        <div class="card-body">
                            <h5 class="fw-bold text-success">CSV Import</h5>
                            <p class="text-muted mb-0">
                                Importer des données via CSV
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div
                        class="card h-100 shadow-sm border-0 cursor-pointer"
                        @click="emit('navigate', 'data-reset')"
                    >
                        <div class="card-body">
                            <h5 class="fw-bold text-danger">Data Reset</h5>
                            <p class="text-muted mb-0">
                                Réinitialiser les données
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
    cursor: pointer;
}

.cursor-pointer {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.cursor-pointer:hover {
    transform: scale(1.02);
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.stat-card {
    border-radius: 15px;
}

.products-card .stat-icon { color: #0d6efd; }
.customers-card .stat-icon { color: #198754; }
.orders-card .stat-icon { color: #ffc107; }

.translate-middle {
    transform: translate(-50%, -50%) !important;
}
</style>
