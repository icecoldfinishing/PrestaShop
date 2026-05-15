<script setup lang="ts">
import { ref, onMounted, watch, reactive } from 'vue';
import { psGet } from '../../../utils/prestashop-api';
import { extractText, psGetProductFullDetails, psGetProductTaxMultiplier, cart, cleanId , getXmlText } from '../../../utils/products/product-api';

type ProductDetail = {
    id: number;
    id_attribute?: number;
    name: string;
    reference: string;
    priceHT: number;
    priceTTC: number;
    taxMultiplier: number;
    description: string;
    imageUrl: string | null;
    features: string[];
    variants: Record<string, string[]>;
    combinations: Array<{ id: string; reference: string; priceImpact: number; options: Record<string, string> }>;
    stockData: Array<{ id_attribute: string, quantity: number }>;
    currentStock: number;
};

const props = defineProps<{ productId: number | null }>();
// Ajout de l'évenement goToCart pour la redirection/protection
const emit = defineEmits<{ (e: 'back'): void, (e: 'goToCart'): void }>();

const product = ref<ProductDetail | null>(null);
const loading = ref(false);
const quantity = ref(1);

const addingToCart = ref(false);

const handleCart = (redirect: boolean = false) => {
    if (!product.value) return;
    applySelectedCombination(product.value);
    addingToCart.value = true;
    const qty = Number.isFinite(quantity.value) ? Math.max(1, quantity.value) : 1;
    cart.add(product.value, qty, { ...selectedOptions })
        .then(() => {
            if (redirect) {
                emit('goToCart');
            }
        })
        .catch(err => {
            console.error('Error adding to cart:', err);
            alert('Une erreur est survenue lors de l\'ajout au panier.');
        })
        .finally(() => {
            addingToCart.value = false;
        });
};

// Pour stocker les choix de l'utilisateur
const selectedOptions = reactive<Record<string, string>>({});

const getImageUrl = (productId: number, imageId: string | number | null) => {
    if (!productId || !imageId) return null;
    return `http://localhost:8088/api/images/products/${productId}/${imageId}`;
};

const loadProduct = async (id: number | null) => {
    if (!id) {
        product.value = null;
        return;
    }

    loading.value = true;
    try {
        const fullData = await psGetProductFullDetails(id);
        const p = fullData.raw;
        const images = p.associations?.images?.image;
        let imageId = Array.isArray(images) ? images[0]?.id : images?.id;
        const priceHT = parseFloat(p.price || '0');
        const taxMultiplier = await psGetProductTaxMultiplier(p);

        product.value = {
            id: Number(p.id),
            name: extractText(p.name),
            reference: p.reference || '',
            priceHT,
            priceTTC: priceHT * taxMultiplier,
            taxMultiplier,
            description: extractText(p.description) || 'Aucune description',
            imageUrl: getImageUrl(Number(p.id), imageId),
            features: fullData.features,
            variants: fullData.variants,
            combinations: fullData.combinations || [],
            stockData: [],
            currentStock: 0
        };

        // Fetch stock data
        try {
            const stockRes = await psGet('stock_availables', '', { 'filter[id_product]': `[${id}]`, display: 'full' });
            const rawStocks = stockRes?.prestashop?.stock_availables?.stock_available;
            const stockArr = rawStocks ? (Array.isArray(rawStocks) ? rawStocks : [rawStocks]) : [];
            product.value.stockData = stockArr.map(s => ({
                id_attribute: cleanId(s.id_product_attribute) || '0',
                quantity: parseInt(getXmlText(s.quantity) || '0', 10)
            }));
        } catch (e) {
            console.error('Error fetching stock:', e);
        }

        // Initialisation des variantes par défaut
        for (const [group, values] of Object.entries(fullData.variants)) {
            selectedOptions[group] = values[0];
        }

        applySelectedCombination(product.value);

        quantity.value = 1;

    } catch (err) {
        console.error('Error fetching product details:', err);
        product.value = null;
    } finally {
        loading.value = false;
    }
};

watch(() => props.productId, (v) => loadProduct(v));
onMounted(() => loadProduct(props.productId ?? null));

watch(
    () => ({ ...selectedOptions }),
    () => {
        if (product.value) {
            applySelectedCombination(product.value);
        }
    }
);

function applySelectedCombination(target: ProductDetail) {
    if (!target.combinations.length) {
        target.id_attribute = 0;
        target.priceTTC = target.priceHT * target.taxMultiplier;
        
        // Update stock for standard product
        const stockInfo = target.stockData.find(s => s.id_attribute === '0');
        target.currentStock = stockInfo ? stockInfo.quantity : 0;
        return;
    }

    const match = target.combinations.find((combo) =>
        Object.entries(combo.options).every(([key, val]) => selectedOptions[key] === val)
    );

    if (!match) {
        target.id_attribute = 0;
        target.priceTTC = target.priceHT * target.taxMultiplier;
        
        const stockInfo = target.stockData.find(s => s.id_attribute === '0');
        target.currentStock = stockInfo ? stockInfo.quantity : 0;
        return;
    }

    const priceHtWithImpact = target.priceHT + match.priceImpact;
    target.priceTTC = priceHtWithImpact * target.taxMultiplier;
    target.reference = match.reference || target.reference;
    target.id_attribute = Number(match.id);

    // Update stock
    const stockInfo = target.stockData.find(s => s.id_attribute === String(target.id_attribute));
    target.currentStock = stockInfo ? stockInfo.quantity : 0;
}
</script>


<template>
<section class="detail container py-4">


    <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-dark"></div>
        <p class="mt-3 text-muted">Chargement du produit...</p>
    </div>

    <div v-else-if="product" class="product-card p-4">
        <div class="row g-5 align-items-start">

            <!-- IMAGE -->
            <div class="col-lg-5">
                <div class="product-image-wrapper">
                    <img
                        v-if="product.imageUrl"
                        :src="product.imageUrl"
                        class="img-fluid product-image"
                    />

                    <div v-else class="media-fallback">
                        <i class="bi bi-image fs-1"></i>
                    </div>
                </div>
            </div>

            <!-- INFOS -->
            <div class="col-lg-7">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <p class="product-ref mb-1">
                            Référence : {{ product.reference }}
                        </p>

                        <h1 class="product-title">
                            {{ product.name }}
                        </h1>
                    </div>

                    <div class="stock-status">
                        <span
                            v-if="product.currentStock > 0"
                            class="badge bg-success fs-6 px-3 py-2"
                        >
                            <i class="bi bi-check-circle me-1"></i>
                            {{ product.currentStock }} en stock
                        </span>

                        <span
                            v-else
                            class="badge bg-danger fs-6 px-3 py-2"
                        >
                            <i class="bi bi-x-circle me-1"></i>
                            Rupture
                        </span>
                    </div>
                </div>

                <!-- PRIX -->
                <div class="price-box">
                    <div class="price-label">
                        Prix TTC
                    </div>

                    <div class="price-value">
                        {{ product.priceTTC.toFixed(2) }} €
                    </div>
                </div>

                <!-- VARIANTES -->
                <div
                    class="variant-box mb-4"
                    v-if="Object.keys(product.variants).length > 0"
                >
                    <div
                        class="mb-3"
                        v-for="(values, groupName) in product.variants"
                        :key="groupName"
                    >
                        <label class="form-label">
                            {{ groupName }}
                        </label>

                        <select
                            class="form-select"
                            v-model="selectedOptions[groupName]"
                        >
                            <option
                                v-for="val in values"
                                :key="val"
                                :value="val"
                            >
                                {{ val }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="row g-3 align-items-end action-buttons">

                    <div class="col-md-3">
                        <div class="qty-box">
                            <label class="form-label fw-semibold">
                                Quantité
                            </label>

                            <input
                                v-model.number="quantity"
                                type="number"
                                min="1"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button
                            class="btn btn-cart w-100"
                            :class="{ active: addingToCart }"
                            @click="handleCart(false)"
                        >
                            <span v-if="!addingToCart">
                                <i class="bi bi-cart-plus me-2"></i>
                                Ajouter
                            </span>

                            <span v-else>
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Ajout...
                            </span>
                        </button>
                    </div>

                    <div class="col-md-5">
                        <button
                            class="btn btn-buy w-100"
                            @click="handleCart(true)"
                        >
                            <i class="bi bi-lightning-charge-fill me-2"></i>
                            Acheter maintenant
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- CARACTÉRISTIQUES -->
        <div
            class="section-card"
            v-if="product.features.length > 0"
        >
            <h3 class="section-title">
                <i class="bi bi-list-check me-2"></i>
                Caractéristiques
            </h3>

            <ul class="feature-list list-unstyled mb-0">
                <li
                    v-for="f in product.features"
                    :key="f"
                >
                    <i class="bi bi-check2-circle text-success me-2"></i>
                    {{ f }}
                </li>
            </ul>
        </div>

        <!-- DESCRIPTION -->
        <div class="section-card">
            <h3 class="section-title">
                <i class="bi bi-file-text me-2"></i>
                Description
            </h3>

            <div
                class="desc"
                v-html="product.description"
            ></div>
        </div>

    </div>
    
    <button class="btn btn-dark back-btn mb-4" @click="emit('back')">
        <i class="bi bi-arrow-left me-2"></i>
        Retour
    </button>
</section>
</template>
<style scoped>
.detail {
    padding: 2rem 0;
    background: #f5f7fb;
    min-height: 100vh;
}

.product-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.back-btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 10px 18px;
}

.product-image-wrapper {
    background: linear-gradient(135deg, #f8f9fa, #eef2f7);
    border-radius: 18px;
    padding: 25px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image {
    max-height: 450px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.03);
}

.media-fallback {
    width: 100%;
    height: 400px;
    border: 2px dashed #ced4da;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    background: #fff;
    font-size: 1.1rem;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.product-ref {
    color: #6c757d;
    font-size: 0.95rem;
}

.price-box {
    background: linear-gradient(135deg, #ff6b00, #ff8c42);
    color: white;
    border-radius: 16px;
    padding: 20px;
    margin: 20px 0;
}

.price-label {
    font-size: 0.9rem;
    opacity: 0.85;
}

.price-value {
    font-size: 2rem;
    font-weight: 700;
}

.variant-box {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.variant-box label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #495057;
}

.form-select,
.form-control {
    border-radius: 12px;
    padding: 12px;
    border: 1px solid #dee2e6;
}

.form-select:focus,
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.15);
    border-color: #ff6b00;
}

.qty-box {
    max-width: 140px;
}

.action-buttons .btn {
    border-radius: 14px;
    padding: 14px;
    font-weight: 600;
    transition: all 0.25s ease;
}

.btn-cart {
    background: #212529;
    color: white;
    border: none;
}

.btn-cart:hover {
    background: #000;
    transform: translateY(-2px);
}

.btn-buy {
    background: linear-gradient(135deg, #ff6b00, #ff8c42);
    color: white;
    border: none;
}

.btn-buy:hover {
    opacity: 0.92;
    transform: translateY(-2px);
}

.btn-cart.active {
    background: #198754;
    animation: pulse 0.5s ease;
}

.feature-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.feature-list li:last-child {
    border-bottom: none;
}

.section-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-top: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.section-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 18px;
    color: #212529;
}

.desc {
    line-height: 1.8;
    color: #495057;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.04);
    }
    100% {
        transform: scale(1);
    }
}

@media (max-width: 991px) {
    .product-title {
        font-size: 1.5rem;
    }

    .price-value {
        font-size: 1.6rem;
    }

    .product-image {
        max-height: 300px;
    }
}
</style>