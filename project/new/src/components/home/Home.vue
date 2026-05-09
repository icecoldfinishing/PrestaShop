<script setup>
import { ref, onMounted } from 'vue';
import { psCount } from '../../utils/prestashop-api';

const stats = ref({
    products: 0,
    customers: 0,
    orders: 0
});

const loading = ref(true);

const loadStats = async () => {
    loading.value = true;
    try {
        const [products, customers, orders] = await Promise.all([
            psCount('products'),
            psCount('customers'),
            psCount('orders')
        ]);
        stats.value = {
            products,
            customers,
            orders
        };
    } catch (error) {
        console.error('Error loading stats:', error);
    } finally {
        loading.value = false;
    }
};
onMounted(() => {
    loadStats();
});
</script>

<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Bienvenue sur PrestaShop Manager</h1>
            <p class="lead text-muted">Gérez votre boutique PrestaShop facilement et rapidement</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Produits</h5>
                        <h2 class="fw-bold text-primary">{{ stats.products }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Clients</h5>
                        <h2 class="fw-bold text-success">{{ stats.customers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="text-muted">Commandes</h5>
                        <h2 class="fw-bold text-warning">{{ stats.orders }}</h2>
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
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    cursor: pointer;
}
</style>