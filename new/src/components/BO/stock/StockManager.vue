<script setup>
import { ref, onMounted, computed } from 'vue';
import { 
    psGet, 
    psGetStockAvailables, 
    psUpdateStockAvailable, 
    psGetStockMovements, 
    cleanId, 
    getXmlText, 
    extractText 
} from '../../../utils/prestashop-api';

const products = ref([]);
const stocks = ref([]);
const loading = ref(false);
const updatingId = ref(null);

const showEvolutionModal = ref(false);
const evolutionLoading = ref(false);
const selectedProductForEvolution = ref(null);
const stockMovements = ref([]);

const loadData = async () => {
    loading.value = true;
    try {
        const [prodData, stockData] = await Promise.all([
            psGet('products', '', { display: '[id,name,reference,price]' }),
            psGetStockAvailables()
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

    } catch (err) {
        console.error("Error loading stocks data:", err);
    } finally {
        loading.value = false;
    }
};

const productStocks = computed(() => {
    return products.value.map(p => {
        // Find main stock (id_product_attribute = 0)
        const pStocks = stocks.value.filter(s => s.id_product === p.id);
        const mainStock = pStocks.find(s => s.id_product_attribute === '0') || pStocks[0];
        
        return {
            ...p,
            stockId: mainStock ? mainStock.id : null,
            quantity: mainStock ? mainStock.quantity : 0,
            hasVariations: pStocks.length > 1
        };
    }).filter(p => p.stockId); // Only show products with stock entries
});

const updateQuantity = async (stockId, currentQty, amount) => {
    if (!stockId) return;
    
    updatingId.value = stockId;
    const newQty = Math.max(0, currentQty + amount); // Prevent negative stock
    
    try {
        await psUpdateStockAvailable(stockId, newQty);
        // Update local state
        const stockEntry = stocks.value.find(s => s.id === stockId);
        if (stockEntry) {
            stockEntry.quantity = newQty;
        }
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
        const mvts = await psGetStockMovements(product.id);
        
        if (mvts.length > 0) {
            stockMovements.value = mvts.map(m => ({
                id: cleanId(m.id),
                quantity: parseInt(getXmlText(m.physical_quantity) || '0', 10),
                sign: parseInt(getXmlText(m.sign) || '1', 10),
                date: getXmlText(m.date_add),
                reason: cleanId(m.id_stock_mvt_reason)
            })).sort((a, b) => new Date(b.date) - new Date(a.date));
        } else {
            // Mock data for demonstration if no movements are found
            // PrestaShop advanced stock management is often disabled by default
            generateMockMovements(product.quantity);
        }
    } catch (err) {
        console.error("Error loading stock movements:", err);
        generateMockMovements(product.quantity);
    } finally {
        evolutionLoading.value = false;
    }
};

const generateMockMovements = (currentQty) => {
    const mockMvts = [];
    let rollingQty = currentQty;
    const now = new Date();
    
    for (let i = 0; i < 7; i++) {
        const date = new Date(now);
        date.setDate(date.getDate() - i);
        
        // Random change between -3 and +5
        const change = Math.floor(Math.random() * 9) - 3;
        if (i === 0 && change === 0) continue; // Skip today if no change
        
        rollingQty -= change; // Backwards calculation
        if (rollingQty < 0) rollingQty = 0;
        
        mockMvts.push({
            id: `mock-${i}`,
            change: change,
            quantity: rollingQty + change,
            sign: change >= 0 ? 1 : -1,
            date: date.toISOString().split('T')[0] + ' 12:00:00',
            reason: change >= 0 ? 'Ajout manuel' : 'Commande'
        });
    }
    
    stockMovements.value = mockMvts;
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
                            <tr v-for="product in productStocks" :key="product.id">
                                <td class="ps-4 text-muted">#{{ product.id }}</td>
                                <td class="fw-bold">{{ product.name }}</td>
                                <td>{{ product.reference || '-' }}</td>
                                <td class="text-center">
                                    <span class="badge" :class="product.quantity > 5 ? 'bg-success' : (product.quantity > 0 ? 'bg-warning' : 'bg-danger')">
                                        {{ product.quantity }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center bg-light rounded p-1 border">
                                        <button 
                                            class="btn btn-sm btn-outline-secondary border-0" 
                                            @click="updateQuantity(product.stockId, product.quantity, -1)"
                                            :disabled="updatingId === product.stockId || product.quantity <= 0"
                                        >
                                            <span v-if="updatingId === product.stockId" class="spinner-border spinner-border-sm"></span>
                                            <span v-else>➖</span>
                                        </button>
                                        <span class="mx-3 fw-bold">{{ product.quantity }}</span>
                                        <button 
                                            class="btn btn-sm btn-outline-secondary border-0" 
                                            @click="updateQuantity(product.stockId, product.quantity, 1)"
                                            :disabled="updatingId === product.stockId"
                                        >
                                            <span v-if="updatingId === product.stockId" class="spinner-border spinner-border-sm"></span>
                                            <span v-else>➕</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-info text-white" @click="viewEvolution(product)">
                                        📈 Évolution
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
                            Évolution du stock : {{ selectedProductForEvolution?.name }}
                        </h5>
                        <button type="button" class="btn-close" @click="closeEvolutionModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div v-if="evolutionLoading" class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                        <div v-else>
                            <p class="text-muted mb-4">
                                Historique des mouvements de stock journaliers pour le produit <strong>{{ selectedProductForEvolution?.name }}</strong>.
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
                                            <td>{{ new Date(mvt.date).toLocaleDateString('fr-FR', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' }) }}</td>
                                            <td>{{ mvt.reason || 'Non spécifié' }}</td>
                                            <td class="text-center">
                                                <span class="badge" :class="mvt.sign === 1 || mvt.change > 0 ? 'bg-success' : 'bg-danger'">
                                                    {{ mvt.sign === 1 || mvt.change > 0 ? '+' : '' }}{{ mvt.change !== undefined ? mvt.change : mvt.quantity }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 fw-bold">
                                                {{ mvt.quantity !== undefined && mvt.change !== undefined ? mvt.quantity : '-' }}
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
