<script setup>
import { ref, onMounted, computed } from 'vue';
import {
    psGet
} from '../../../utils/prestashop-api';
import {
    psGetStockAvailables,
    psUpdateStockAvailable,
    psGetStockMovementsFromOrders,
} from '../../../utils/stocks/stock-api';
import {
    cleanId,
    getXmlText,
    extractText
} from '../../../utils/products/product-api';

const products = ref([]);
const stocks = ref([]);
const combinations = ref([]);
const optionValues = ref({});
const loading = ref(false);
const updatingId = ref(null);

const showEvolutionModal = ref(false);
const evolutionLoading = ref(false);
const selectedProductForEvolution = ref(null);
const stockMovements = ref([]);

// Cache pour garder l'évolution fixe par produit + déclinaison (mock)
const mockMovementsCache = {};

const loadData = async () => {
    loading.value = true;
    try {
        const [prodData, stockData, comboData, optValData] = await Promise.all([
            psGet('products', '', { display: '[id,name,reference,price]' }),
            psGetStockAvailables(),
            psGet('combinations', '', { display: 'full' }),
            psGet('product_option_values', '', { display: 'full' })
        ]);

        const rawProducts = prodData?.prestashop?.products?.product || [];
        const productList = Array.isArray(rawProducts) ? rawProducts : [rawProducts];

        products.value = productList.map(p => ({
            id: cleanId(p.id),
            name: extractText(p.name),
            reference: getXmlText(p.reference),
            price: parseFloat(getXmlText(p.price) || '0').toFixed(2)
        }));

        stocks.value = stockData.map(s => ({
            id: cleanId(s.id),
            id_product: cleanId(s.id_product),
            id_product_attribute: cleanId(s.id_product_attribute),
            quantity: parseInt(getXmlText(s.quantity) || '0', 10)
        }));

        // Map combinations
        const rawCombos = [].concat(comboData?.prestashop?.combinations?.combination || []);
        combinations.value = rawCombos.map(c => ({
            id: cleanId(c.id),
            id_product: cleanId(c.id_product),
            option_values: [].concat(c.associations?.product_option_values?.product_option_value || []).map(v => cleanId(v.id))
        }));

        // Map option values
        const rawOpts = [].concat(optValData?.prestashop?.product_option_values?.product_option_value || []);
        rawOpts.forEach(o => {
            optionValues.value[cleanId(o.id)] = extractText(o.name);
        });

    } catch (err) {
        console.error("Error loading stocks data:", err);
    } finally {
        loading.value = false;
    }
};

const productStocks = computed(() => {
    const list = [];

    products.value.forEach(p => {
        // Vérifie si ce produit a de vraies combinaisons déclarées
        const productCombinations = combinations.value.filter(c => c.id_product === p.id);

        if (productCombinations.length > 0) {
            // ── Produit avec déclinaisons ──
            // On affiche uniquement les stocks liés à une déclinaison (attr ≠ '0')
            productCombinations.forEach(combo => {
                const s = stocks.value.find(
                    s => s.id_product === p.id && s.id_product_attribute === combo.id
                );
                if (!s) return; // pas de stock pour cette combo, on skip

                const variationName =
                    combo.option_values
                        .map(vId => optionValues.value[vId])
                        .filter(Boolean)
                        .join(', ') || 'Déclinaison';

                list.push({
                    ...p,
                    displayName: `${p.name} - ${variationName}`,
                    stockId: s.id,
                    quantity: s.quantity,
                    id_product_attribute: s.id_product_attribute
                });
            });
        } else {
            // ── Produit simple (sans déclinaisons) ──
            const mainStock = stocks.value.find(
                s => s.id_product === p.id &&
                    (s.id_product_attribute === '0' || s.id_product_attribute === '')
            );
            if (!mainStock) return;

            list.push({
                ...p,
                displayName: p.name,
                stockId: mainStock.id,
                quantity: mainStock.quantity,
                id_product_attribute: '0'
            });
        }
    });

    return list;
});

const updateQuantity = async (product, amount) => {
    const stockId = product.stockId;
    if (!stockId) return;

    updatingId.value = stockId;
    const currentQty = product.quantity;
    const newQty = Math.max(0, currentQty + amount);

    try {
        await psUpdateStockAvailable(stockId, newQty);
        // Update local state
        const stockEntry = stocks.value.find(s => s.id === stockId);
        if (stockEntry) {
            stockEntry.quantity = newQty;
        }

        // Add to cache
        const productId = product.id;
        const attrId = product.id_product_attribute;
        const cacheKey = `${productId}-${attrId}`;

        if (!mockMovementsCache[cacheKey]) {
            mockMovementsCache[cacheKey] = [];
        }
        mockMovementsCache[cacheKey].unshift({
            id: `manual-${Date.now()}`,
            change: amount,
            quantity: newQty,
            sign: amount > 0 ? 1 : -1,
            date: new Date().toISOString().replace('T', ' ').slice(0, 19),
            reason: 'Ajustement manuel'
        });

    } catch (err) {
        console.error("Error updating stock:", err);
        alert("Erreur lors de la mise à jour du stock.");
    } finally {
        updatingId.value = null;
    }
};

const viewEvolution = async (product) => {
    selectedProductForEvolution.value = product;
    showEvolutionModal.value = true;
    evolutionLoading.value = true;
    stockMovements.value = [];

    try {
        const orderMvts = await psGetStockMovementsFromOrders(product.id, product.id_product_attribute);
        const cacheKey = `${product.id}-${product.id_product_attribute}`;
        const manualMvts = mockMovementsCache[cacheKey] || [];

        // Combine real orders and manual adjustments
        const allMvts = [...orderMvts, ...manualMvts].sort((a, b) => new Date(b.date) - new Date(a.date));

        // Calculate rolling quantity backwards from current stock
        let rollingQty = product.quantity;

        const finalMvts = allMvts.map(m => {
            const calculatedQty = rollingQty;
            rollingQty = rollingQty - m.change; // reverse the operation
            return {
                ...m,
                quantity: calculatedQty
            };
        });

        // Ajouter l'insertion de base à la fin
        finalMvts.push({
            id: 'initial',
            change: rollingQty,
            quantity: rollingQty,
            sign: 1,
            date: 'Initial',
            reason: 'Insertion de base'
        });

        stockMovements.value = finalMvts;

    } catch (err) {
        console.error("Error loading stock movements:", err);
    } finally {
        evolutionLoading.value = false;
    }
};

const closeEvolutionModal = () => {
    showEvolutionModal.value = false;
    selectedProductForEvolution.value = null;
};

onMounted(() => {
    loadData();
});
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-3">Gestion des Stocks</h2>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>

        <div v-else class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Produit</th>
                                <th>Référence</th>
                                <th class="text-center">Quantité actuelle</th>
                                <th class="text-center">Ajuster (Ajouter/Enlever)</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="productStocks.length === 0">
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Aucun produit trouvé.
                                </td>
                            </tr>
                            <tr v-for="product in productStocks" :key="product.stockId">
                                <td class="ps-4 text-muted">#{{ product.id }}</td>
                                <td class="fw-bold">{{ product.displayName }}</td>
                                <td>{{ product.reference || '-' }}</td>
                                <td class="text-center">
                                    <span class="badge"
                                        :class="product.quantity > 5 ? 'bg-success' : (product.quantity > 0 ? 'bg-warning' : 'bg-danger')">
                                        {{ product.quantity }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center bg-light rounded p-1 border">
                                        <button class="btn btn-sm btn-outline-secondary border-0"
                                            @click="updateQuantity(product, -1)"
                                            :disabled="updatingId === product.stockId || product.quantity <= 0">
                                            <span v-if="updatingId === product.stockId"
                                                class="spinner-border spinner-border-sm"></span>
                                            <span v-else>➖</span>
                                        </button>
                                        <span class="mx-3 fw-bold">{{ product.quantity }}</span>
                                        <button class="btn btn-sm btn-outline-secondary border-0"
                                            @click="updateQuantity(product, 1)"
                                            :disabled="updatingId === product.stockId">
                                            <span v-if="updatingId === product.stockId"
                                                class="spinner-border spinner-border-sm"></span>
                                            <span v-else>➕</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-info text-white" @click="viewEvolution(product)">
                                        Évolution
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Évolution du stock -->
        <div v-if="showEvolutionModal" class="modal-backdrop fade show"></div>
        <div v-if="showEvolutionModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            Évolution du stock : {{ selectedProductForEvolution?.displayName }}
                        </h5>
                        <button type="button" class="btn-close" @click="closeEvolutionModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div v-if="evolutionLoading" class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                        <div v-else>
                            <p class="text-muted mb-4">
                                Historique des mouvements de stock journaliers pour le produit <strong>{{
                                    selectedProductForEvolution?.displayName }}</strong>.
                            </p>

                            <div class="table-responsive border rounded">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Raison / Action</th>
                                            <th class="text-center">Mouvement</th>
                                            <th class="text-end pe-3">Quantité Résultante</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="stockMovements.length === 0">
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                Aucun mouvement de stock enregistré.
                                            </td>
                                        </tr>
                                        <tr v-for="mvt in stockMovements" :key="mvt.id">
                                            <td>{{ new Date(mvt.date).toLocaleDateString('fr-FR', {
                                                weekday: 'short',
                                                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit',
                                                minute:'2-digit' }) }}</td>
                                            <td>{{ mvt.reason || 'Non spécifié' }}</td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    :class="mvt.sign === 1 || mvt.change > 0 ? 'bg-success' : 'bg-danger'">
                                                    {{ mvt.sign === 1 || mvt.change > 0 ? '+' : '' }}{{ mvt.change !==
                                                    undefined ? mvt.change : mvt.quantity }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 fw-bold">
                                                {{ mvt.quantity !== undefined && mvt.change !== undefined ? mvt.quantity
                                                : '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeEvolutionModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
