<script setup>
import { ref, onMounted } from 'vue';
import { psCount, psGetOrdersDashboardStats } from '../../../utils/prestashop-api';

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
        const { byDay, totals } = await psGetOrdersDashboardStats();
        ordersByDay.value = byDay;
        ordersTotals.value = totals;
    } catch (e) {
        console.error(e);
        dashboardError.value = 'Impossible de charger les statistiques commandes.';
        ordersByDay.value = [];
        ordersTotals.value = { count: 0, amount: 0 };
    } finally {
        dashboardLoading.value = false;
    }
};

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
                <div class="card shadow-sm border-0 hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Produits</h5>
                        <h2 class="fw-bold text-primary">{{ stats.products }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Clients</h5>
                        <h2 class="fw-bold text-success">{{ stats.customers }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Commandes (total)</h5>
                        <h2 class="fw-bold text-warning">{{ stats.orders }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLEAU DE BORD COMMANDES -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold">Tableau de bord — commandes</h4>
                <p class="text-muted small mb-0 mt-1">
                    Par jour : nombre de commandes et montant (<code>total_paid</code>). Total général en bas.
                </p>
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
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Jour</th>
                                    <th scope="col" class="text-end">Nb commandes</th>
                                    <th scope="col" class="text-end">Montant (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in ordersByDay" :key="row.date">
                                    <td class="font-monospace">{{ row.date }}</td>
                                    <td class="text-end">{{ row.count }}</td>
                                    <td class="text-end fw-semibold">{{ formatMoney(row.amount) }}</td>
                                </tr>
                                <tr v-if="!ordersByDay.length">
                                    <td colspan="3" class="text-muted text-center py-4">
                                        Aucune commande à afficher.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot v-if="ordersByDay.length" class="table-group-divider">
                                <tr class="fw-bold">
                                    <td>Total général</td>
                                    <td class="text-end">{{ ordersTotals.count }}</td>
                                    <td class="text-end text-primary">{{ formatMoney(ordersTotals.amount) }} €</td>
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
                <div class="col-md-4">
                    <div
                        class="card h-100 shadow-sm border-0 cursor-pointer"
                        @click="emit('navigate', 'api')"
                    >
                        <div class="card-body">
                            <h5 class="fw-bold text-primary">API Viewer</h5>
                            <p class="text-muted mb-0">
                                Réponses API PrestaShop
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
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

                <div class="col-md-4">
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
</style>
