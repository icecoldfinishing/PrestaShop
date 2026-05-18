<script setup>
import { ref, onMounted, computed } from 'vue';
import { psGet } from '../../../utils/prestashop-api';
import {
    cleanId,
    getXmlText,
    extractText
} from '../../../utils/products/product-api';

/**
 * =========================================================
 * CONFIG
 * =========================================================
 */
const API_URL = '/api';
const WS_KEY = 'Q2C19X4XAS6JFE2AHQCEMFZC7IY24LH5';

/**
 * =========================================================
 * STATE
 * =========================================================
 */
const products = ref([]);
const stocks = ref([]);
const combinations = ref([]);
const optionValues = ref({});

const expandedProducts = ref({});

const loading = ref(false);
const updatingId = ref(null); // Contient le stockId en cours de traitement pour bloquer l'interface

const showEvolutionModal = ref(false);
const evolutionLoading = ref(false);

const selectedProductForEvolution = ref(null);
const stockMovements = ref([]);

/**
 * =========================================================
 * LOAD DATA
 * =========================================================
 */
const loadData = async () => {
    loading.value = true;
    try {
        const [
            prodData,
            stockData,
            comboData,
            optValData
        ] = await Promise.all([
            psGet('products', '', { display: '[id,name,reference,price,date_add,available_date]' }), // <-- AJOUT date_add et available_date ici
            psGet('stock_availables', '', { display: 'full' }),
            psGet('combinations', '', { display: 'full' }), // Note: PrestaShop stocke souvent la date au niveau produit, mais si tes combinaisons ont un date_add, il est pris aussi
            psGet('product_option_values', '', { display: 'full' })
        ]);

        const rawProducts = prodData?.prestashop?.products?.product || [];
        const productList = Array.isArray(rawProducts) ? rawProducts : [rawProducts];
        products.value = productList.map(p => ({
            id: cleanId(p.id),
            name: extractText(p.name),
            reference: getXmlText(p.reference),
            price: parseFloat(getXmlText(p.price) || '0').toFixed(2),
            date_add: getXmlText(p.date_add),
            available_date: getXmlText(p.available_date)
        }));

        const rawStocks = [].concat(stockData?.prestashop?.stock_availables?.stock_available || []);
        stocks.value = rawStocks.map(s => ({
            id: cleanId(s.id),
            id_product: cleanId(s.id_product),
            id_product_attribute: cleanId(s.id_product_attribute),
            quantity: parseInt(getXmlText(s.quantity) || '0', 10),           // disponible (lecture seule, affiché en info)
            physical_quantity: parseInt(getXmlText(s.physical_quantity) || '0', 10)  // physique (modifiable)
        }));

        const rawCombos = [].concat(comboData?.prestashop?.combinations?.combination || []);
        combinations.value = rawCombos.map(c => ({
            id: cleanId(c.id),
            id_product: cleanId(c.id_product),
            option_values: [].concat(c.associations?.product_option_values?.product_option_value || []).map(v => cleanId(v.id))
        }));

        const rawOpts = [].concat(optValData?.prestashop?.product_option_values?.product_option_value || []);
        rawOpts.forEach(o => {
            optionValues.value[cleanId(o.id)] = extractText(o.name);
        });

    } catch (err) {
        console.error('Erreur chargement stocks:', err);
    } finally {
        loading.value = false;
    }
};

const toggleExpand = (productId) => {
    expandedProducts.value[productId] = !expandedProducts.value[productId];
};

/**
 * =========================================================
 * COMPUTED: HIERARCHICAL PRODUCTS FOR TABLE
 * =========================================================
 */
const productRows = computed(() => {
    return products.value.map(p => {
        const productCombos = combinations.value.filter(c => c.id_product === p.id);
        const hasCombinations = productCombos.length > 0;

        const children = productCombos.map(combo => {
            const variationName = combo.option_values
                .map(vId => optionValues.value[vId])
                .filter(Boolean)
                .join(', ') || `Déclinaison #${combo.id}`;

            const comboStock = stocks.value.find(s =>
                s.id_product === p.id && String(s.id_product_attribute) === String(combo.id)
            );

            return {
                id: combo.id,
                name: variationName,
                stockId: comboStock ? comboStock.id : null,
                quantity: comboStock ? comboStock.quantity : 0,
                physical_quantity: comboStock ? comboStock.physical_quantity : 0,
                attributeId: combo.id,
                reference: p.reference,
                date_add: p.date_add, // <-- Les déclinaisons héritent de la date du produit par défaut
                available_date: p.available_date
            };
        });

        const mainStock = stocks.value.find(s =>
            s.id_product === p.id && String(s.id_product_attribute) === '0'
        );

        const totalQuantity = hasCombinations
            ? children.reduce((sum, child) => sum + child.quantity, 0)
            : (mainStock ? mainStock.quantity : 0);
        const totalPhysical = hasCombinations
            ? children.reduce((sum, child) => sum + child.physical_quantity, 0)
            : (mainStock ? mainStock.physical_quantity : 0);

        return {
            ...p,
            hasCombinations,
            children,
            stockId: mainStock ? mainStock.id : null,
            quantity: totalQuantity,
            physical_quantity: totalPhysical,
            isExpanded: !!expandedProducts.value[p.id],
            date_add: p.date_add, // <-- AJOUTER cette ligne pour le produit simple
            available_date: p.available_date
        };
    });
});

/**
 * =========================================================
 * UPDATE QUANTITY
 * =========================================================
 */
const updateQuantity = async (target, delta) => {
    if (!target.stockId || delta === 0) return;
    updatingId.value = target.stockId;

    const attributeId = target.attributeId || '0';
    const productId = target.id_product || target.id;

    try {
        const xmlData = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <stock_delta>
        <id_product>${productId}</id_product>
        <id_product_attribute>${attributeId}</id_product_attribute>
        <delta>${delta}</delta>
    </stock_delta>
</prestashop>`;

        const response = await fetch(`${API_URL}/stock_deltas?ws_key=${WS_KEY}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/xml',
                'Accept': 'application/xml'
            },
            body: xmlData
        });

        if (!response.ok) {
            const errText = await response.text();
            throw new Error(`HTTP ${response.status}\n${errText}`);
        }

        // Mutation locale sécurisée après validation API
        const stockEntry = stocks.value.find(s => s.id === target.stockId);
        if (stockEntry) {
            const updatedPhysical = stockEntry.physical_quantity + delta;
            stockEntry.physical_quantity = updatedPhysical < 0 ? 0 : updatedPhysical;
        }

        // Resynchronisation stricte depuis le serveur PrestaShop
        await refreshSingleStock(productId, attributeId);

    } catch (err) {
        console.error('Erreur update stock:', err);
        alert(`Erreur mise à jour stock:\n${err.message}`);
    } finally {
        updatingId.value = null;
    }
};

/**
 * Gère la validation manuelle (Appui sur Entrée ou Perte de Focus)
 */
const handleManualInput = (event, target) => {
    const newValue = parseInt(event.target.value, 10);

    if (isNaN(newValue) || newValue < 0) {
        event.target.value = target.quantity;
        return;
    }

    const delta = newValue - target.physical_quantity;
    if (delta !== 0) {
        updateQuantity(target, delta);
    }
};

/**
 * Réinitialise la valeur visuelle si l'utilisateur annule ou appuie sur Échap
 */
const handleResetInput = (event, currentQty) => {
    event.target.value = currentQty;
    event.target.blur();
};

/**
 * =========================================================
 * REFRESH SINGLE STOCK
 * =========================================================
 */
const refreshSingleStock = async (productId, productAttributeId) => {
    try {
        const data = await psGet('stock_availables', '', {
            [`filter[id_product]`]: productId,
            display: 'full'
        });

        const raw = [].concat(data?.prestashop?.stock_availables?.stock_available || []);
        const found = raw.find(s => cleanId(s.id_product_attribute) === String(productAttributeId));

        if (!found) return;

        const stockEntry = stocks.value.find(s =>
            s.id_product === String(productId) &&
            s.id_product_attribute === String(productAttributeId)
        );

        if (stockEntry) {
            stockEntry.quantity = parseInt(getXmlText(found.quantity) || '0', 10);
            stockEntry.physical_quantity = parseInt(getXmlText(found.physical_quantity) || '0', 10);  // ← AJOUTER
        }
    } catch (err) {
        console.error('Erreur refresh stock:', err);
    }
};

/**
 * =========================================================
 * VIEW EVOLUTION (MODAL)
 * =========================================================
 */
const viewEvolution = async (productRow, attributeId = '0', displayName = '') => {
    selectedProductForEvolution.value = {
        name: productRow.name,
        displayName: displayName || productRow.name
    };
    showEvolutionModal.value = true;
    evolutionLoading.value = true;
    stockMovements.value = [];

    try {
        // 1. Récupérer les stock_deltas
        const deltasData = await psGet('stock_deltas', '', {
            [`filter[id_product]`]: productRow.id,
            display: 'full'
        });
        const rawDeltas = [].concat(deltasData?.prestashop?.stock_deltas?.stock_delta || []);
        const filteredDeltas = rawDeltas.filter(m => cleanId(m.id_product_attribute || '0') === String(attributeId));

        // 2. Récupérer les mouvements issus des commandes

        // Copie mutable des commandes pour le tracking des correspondances
        const correlatedMovements = [];

        // 3. Traiter les stock_deltas et chercher une correspondance avec les commandes
        for (const mvt of filteredDeltas) {
            const delta = parseInt(getXmlText(mvt.delta) || '0', 10);
            const mvtDate = getXmlText(mvt.date_add);
            let reason = delta > 0 ? 'Ajout manuel' : 'Retrait manuel';

            if (delta < 0) {
                const targetQty = Math.abs(delta);
                const mvtTime = new Date(mvtDate).getTime();

                let bestMatchIdx = -1;
                let bestTimeDiff = Infinity;


            }

            correlatedMovements.push({
                id: cleanId(mvt.id),
                date: mvtDate,
                reason,
                change: Math.abs(delta),
                sign: delta > 0 ? 1 : -1,
                delta
            });
        }


        // 5. Trier tous les mouvements par date décroissante
        correlatedMovements.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime());

        // 6. Calculer les stocks cumulés à reculons à partir de la quantité actuelle
        const targetStock = attributeId === '0'
            ? productRow.quantity
            : (productRow.children.find(c => c.id === attributeId)?.quantity || 0);

        let rollingQty = targetStock;
        const processed = correlatedMovements.map(mvt => {
            const currentQty = rollingQty;
            rollingQty -= mvt.delta;
            return {
                id: mvt.id,
                date: mvt.date,
                reason: mvt.reason,
                change: mvt.change,
                sign: mvt.sign,
                quantity: currentQty
            };
        });

        // 7. Déterminer la date d'initialisation (available_date, sinon date_add)
        const getValidAvailabilityDate = (row) => {
            const date = row?.available_date;
            if (date && date !== '0000-00-00 00:00:00' && date !== '0000-00-00' && date.trim() !== '') {
                return date;
            }
            return row?.date_add;
        };

        const targetChild = attributeId === '0' ? null : productRow.children.find(c => c.id === attributeId);
        const creationDate = targetChild ? getValidAvailabilityDate(targetChild) : getValidAvailabilityDate(productRow);

        processed.push({
            id: 'initial',
            date: creationDate,
            reason: 'Stock historique',
            change: rollingQty,
            sign: 1,
            quantity: rollingQty
        });

        stockMovements.value = processed;
    } catch (err) {
        console.error('Erreur historique stock:', err);
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
                    <table class="table align-middle mb-0 custom-stock-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;"></th>
                                <th style="width: 100px;">ID</th>
                                <th>Produit / Déclinaison</th>
                                <th style="width: 180px;">Référence</th>
                                <th class="text-center" style="width: 180px;">Quantité actuelle</th>
                                <th class="text-center" style="width: 280px;">Ajuster Stock</th>
                                <th class="text-end pe-4" style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="productRows.length === 0">
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucun produit trouvé.
                                </td>
                            </tr>

                            <template v-for="product in productRows" :key="product.id">
                                <!-- LIGNE MÈRE -->
                                <tr
                                    :class="{ 'table-parent-row fw-bold': product.hasCombinations, 'border-bottom-0': product.isExpanded }">
                                    <td class="text-center">
                                        <button v-if="product.hasCombinations"
                                            class="btn btn-sm btn-link text-primary p-0 border-0 toggle-btn"
                                            @click="toggleExpand(product.id)">
                                            <i class="bi fs-5"
                                                :class="product.isExpanded ? 'bi-dash-square' : 'bi-plus-square'"></i>
                                        </button>
                                    </td>

                                    <td class="text-muted">#{{ product.id }}</td>
                                    <td>
                                        <span class="product-title">{{ product.name }}</span>
                                        <span v-if="product.hasCombinations"
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2 small-badge">
                                            {{ product.children.length }} déclinaisons
                                        </span>
                                    </td>
                                    <td>{{ product.reference || '-' }}</td>

                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge px-3 py-2 fs-6 shadow-xs"
                                                :class="product.physical_quantity > 5 ? 'bg-success' : (product.physical_quantity > 0 ? 'bg-warning text-dark' : 'bg-danger')">
                                                {{ product.physical_quantity }} {{ product.hasCombinations ? 'Total' :
                                                    '' }}
                                            </span>
                                            <span class="text-muted" style="font-size:0.72rem;">
                                                Dispo : {{ product.physical_quantity }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div v-if="!product.hasCombinations"
                                            class="d-inline-flex align-items-center bg-light rounded p-1 border">
                                            <button class="btn btn-sm btn-outline-secondary border-0 icon-btn"
                                                @click="updateQuantity(product, -1)"
                                                :disabled="updatingId === product.stockId || product.physical_quantity <= 0">
                                                <span v-if="updatingId === product.stockId"
                                                    class="spinner-border spinner-border-sm"></span>
                                                <i v-else class="bi bi-dash-lg"></i>
                                            </button>

                                            <!-- INPUT SÉCURISÉ MÈRE -->
                                            <input type="number"
                                                class="form-control form-control-sm text-center mx-2 manual-qty-input"
                                                :value="product.physical_quantity" :disabled="updatingId === product.stockId"
                                                min="0" @keydown.enter.prevent="handleManualInput($event, product)"
                                                @keydown.esc="handleResetInput($event, product.physical_quantity)"
                                                @blur="handleManualInput($event, product)" />

                                            <button class="btn btn-sm btn-outline-secondary border-0 icon-btn"
                                                @click="updateQuantity(product, 1)"
                                                :disabled="updatingId === product.stockId">
                                                <span v-if="updatingId === product.stockId"
                                                    class="spinner-border spinner-border-sm"></span>
                                                <i v-else class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                        <span v-else
                                            class="text-muted small-text italic text-uppercase fw-normal tracking-wide">
                                            <i class="bi bi-arrow-down-short"></i> Déclinaisons ci-dessous
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <button v-if="!product.hasCombinations"
                                            class="btn btn-sm btn-info text-white shadow-sm fw-normal"
                                            @click="viewEvolution(product, '0', product.name)">
                                            <i class="bi bi-graph-up-arrow me-1"></i> Évolution
                                        </button>
                                    </td>
                                </tr>

                                <!-- LIGNES FILLES -->
                                <template v-if="product.hasCombinations && product.isExpanded">
                                    <tr v-for="child in product.children" :key="child.id"
                                        class="table-child-row bg-light-subtle">
                                        <td class="text-center border-end-indent">
                                            <i class="bi bi-arrow-return-right text-muted opacity-50 ms-1"></i>
                                        </td>
                                        <td class="text-muted small-text text-end pe-3">#{{ child.id }}</td>
                                        <td class="ps-3">
                                            <span class="text-secondary fw-semibold">{{ child.name }}</span>
                                        </td>
                                        <td class="text-muted small-text">{{ child.reference || '-' }}</td>

                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <span class="badge px-3 py-2 fs-6 shadow-xs"
                                                    :class="child.physical_quantity > 5 ? 'bg-success' : (child.physical_quantity > 0 ? 'bg-warning text-dark' : 'bg-danger')">
                                                    {{ child.physical_quantity }}
                                                </span>
                                                <span class="text-muted" style="font-size:0.72rem;">
                                                    Dispo : {{ child.quantity }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div
                                                class="d-inline-flex align-items-center bg-white rounded p-1 border shadow-xs">
                                                <button class="btn btn-sm btn-outline-secondary border-0 icon-btn"
                                                    @click="updateQuantity({ ...child, id_product: product.id }, -1)"
                                                    :disabled="updatingId === child.stockId || child.quantity <= 0">
                                                    <span v-if="updatingId === child.stockId"
                                                        class="spinner-border spinner-border-sm"></span>
                                                    <i v-else class="bi bi-dash-lg"></i>
                                                </button>

                                                <!-- INPUT SÉCURISÉ ENFANT -->
                                                <input type="number"
                                                    class="form-control form-control-sm text-center mx-2 manual-qty-input"
                                                    :value="child.quantity" :disabled="updatingId === child.stockId"
                                                    min="0"
                                                    @keydown.enter.prevent="handleManualInput($event, { ...child, id_product: product.id })"
                                                    @keydown.esc="handleResetInput($event, child.quantity)"
                                                    @blur="handleManualInput($event, { ...child, id_product: product.id })" />

                                                <button class="btn btn-sm btn-outline-secondary border-0 icon-btn"
                                                    @click="updateQuantity({ ...child, id_product: product.id }, 1)"
                                                    :disabled="updatingId === child.stockId">
                                                    <span v-if="updatingId === child.stockId"
                                                        class="spinner-border spinner-border-sm"></span>
                                                    <i v-else class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-info shadow-xs fw-normal"
                                                @click="viewEvolution(product, child.attributeId, `${product.name} (${child.name})`)">
                                                <i class="bi bi-graph-up-arrow me-1"></i> Évolution
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL HISTORIQUE -->
        <div v-if="showEvolutionModal" class="modal-backdrop fade show"></div>
        <div v-if="showEvolutionModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-dark">
                            <i class="bi bi-clock-history me-2 text-primary"></i> Évolution du stock : {{
                                selectedProductForEvolution?.displayName }}
                        </h5>
                        <button type="button" class="btn-close" @click="closeEvolutionModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div v-if="evolutionLoading" class="text-center py-4">
                            <div class="spinner-border text-primary"></div>
                        </div>
                        <div v-else>
                            <p class="text-muted mb-4">
                                Historique complet des ajustements pour <strong>{{
                                    selectedProductForEvolution?.displayName
                                }}</strong>.
                            </p>
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-striped table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Date</th>
                                            <th>Raison / Action</th>
                                            <th class="text-center">Mouvement</th>
                                            <th class="text-end pe-4">Quantité Résultante</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="stockMovements.length === 0">
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                Aucun mouvement de stock enregistré.
                                            </td>
                                        </tr>
                                        <tr v-for="mvt in stockMovements" :key="mvt.id">
                                            <td class="ps-3">
                                                {{ new Date(mvt.date).toLocaleDateString('fr-FR', {
                                                    weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour:
                                                        '2-digit', minute: '2-digit'
                                                }) }}
                                            </td>
                                            <td>{{ mvt.reason }}</td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    :class="mvt.id === 'initial' ? 'bg-secondary' : (mvt.sign === 1 ? 'bg-success' : 'bg-danger')">
                                                    {{ mvt.id === 'initial' ? '' : (mvt.sign === 1 ? '+' : '-') }}{{
                                                        mvt.change
                                                    }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 fw-bold">
                                                {{ mvt.quantity }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" @click="closeEvolutionModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4);
}

.manual-qty-input {
    width: 70px;
    font-weight: bold;
    border-color: #dee2e6;
}

.manual-qty-input::-webkit-outer-spin-button,
.manual-qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.manual-qty-input[type=number] {
    -moz-appearance: textfield;
}

.small-text {
    font-size: 0.825rem;
}

.small-badge {
    font-size: 0.75rem;
    padding: 0.25em 0.6em;
}

.italic {
    font-style: italic;
}

.tracking-wide {
    letter-spacing: 0.05em;
}

.shadow-xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.custom-stock-table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.03em;
    padding-top: 12px;
    padding-bottom: 12px;
}

.table-parent-row {
    background-color: #ffffff;
}

.table-child-row {
    background-color: #f8f9fa !important;
    border-left: 3px solid #0d6efd;
}

.table-child-row td {
    padding-top: 8px;
    padding-bottom: 8px;
}

.border-end-indent {
    background-color: #f1f3f5;
}

.toggle-btn {
    text-decoration: none;
    transition: transform 0.2s;
}

.toggle-btn:hover {
    transform: scale(1.1);
}

.icon-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.product-title {
    font-size: 0.95rem;
}
</style>