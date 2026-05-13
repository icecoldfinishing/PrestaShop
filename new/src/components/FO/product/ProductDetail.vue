<script setup lang="ts">
import { ref, onMounted, watch, reactive } from 'vue';
import { extractText, psGetProductFullDetails, psGetProductTaxMultiplier, cart } from '../../../utils/prestashop-api';

type ProductDetail = {
    id: number;
    name: string;
    reference: string;
    priceHT: number;
    priceTTC: number;
    description: string;
    imageUrl: string | null;
    features: string[];
    variants: Record<string, string[]>;
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
            description: extractText(p.description) || 'Aucune description',
            imageUrl: getImageUrl(Number(p.id), imageId),
            features: fullData.features,
            variants: fullData.variants,
        };

        // Initialisation des variantes par défaut
        for (const [group, values] of Object.entries(fullData.variants)) {
            selectedOptions[group] = values[0];
        }

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
</script>

<template>
    <section class="detail">
        <button class="back" @click="emit('back')">← Retour</button>

        <div v-if="loading" class="state">Chargement...</div>

        <div v-else-if="product" class="layout">
            <div class="media">
                <img v-if="product.imageUrl" :src="product.imageUrl" />
                <div v-else class="media-fallback">Pas d'image</div>
            </div>

            <div class="info">
                <p class="meta">Ref: {{ product.reference }}</p>
                <h2>{{ product.name }}</h2>

                <div class="price">
                    {{ product.priceTTC.toFixed(2) }} €
                </div>

                <!-- SELECTEURS DE VARIANTES DYNAMIQUES -->
                <div class="selectors" v-if="Object.keys(product.variants).length > 0">
                    <div v-for="(values, groupName) in product.variants" :key="groupName" class="select-group">
                        <label>{{ groupName }}</label>
                        <!-- Utilisation de v-model pour capturer le choix -->
                        <select v-model="selectedOptions[groupName]">
                            <option v-for="val in values" :key="val" :value="val">
                                {{ val }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- BOUTONS D'ACTION -->
                <div class="actions">
                    <div class="qty">
                        <label>Quantite</label>
                        <input v-model.number="quantity" type="number" min="1" />
                    </div>
                    <button class="btn-add" :class="{ active: addingToCart }" @click="handleCart(false)">
                        <span v-if="!addingToCart">Ajouter au panier</span>
                        <span v-else>Ajout...</span>
                    </button> <button class="btn-buy" @click="handleCart(true)">Acheter maintenant</button>
                </div>

                <div class="section specs" v-if="product.features.length > 0">
                    <h3>Caractéristiques</h3>
                    <ul>
                        <li v-for="f in product.features" :key="f">{{ f }}</li>
                    </ul>
                </div>

                <div class="section">
                    <h3>Description</h3>
                    <div class="desc" v-html="product.description"></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Tes styles restent identiques */
.detail {
    padding: 24px;
    font-family: sans-serif;
}

.back {
    background: #111;
    color: white;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    margin-bottom: 20px;
}

.layout {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 30px;
}

.media img {
    max-width: 100%;
    border-radius: 8px;
}

.price {
    font-size: 24px;
    font-weight: bold;
    color: #e85d04;
    margin-bottom: 20px;
}

.selectors {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.select-group {
    display: flex;
    flex-direction: column;
}

.select-group label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #555;
}

.select-group select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-add,
.btn-buy {
    flex: 1;
    padding: 15px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    border: none;
    transition: opacity 0.2s;
}

.btn-add {
    background: #f0f0f0;
    color: #111;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}


.btn-buy {
    background: #e85d04;
    color: white;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}


.qty {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 120px;
}

.qty label {
    font-weight: bold;
    color: #555;
}

.qty input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.section {
    margin-top: 20px;
}

.specs ul {
    list-style: disc;
    padding-left: 20px;
}

.desc {
    line-height: 1.5;
}
/* Click animation */
.btn-add:active {
    transform: scale(0.96);
}

/* État actif (ajout panier) */
.btn-add.active {
    background: #2ecc71;
    color: white;
    border-color: #2ecc71;
    animation: pulse 0.6s ease;
}

/* Effet pulse */
@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 rgba(46, 204, 113, 0.5);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(46, 204, 113, 0.6);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 rgba(46, 204, 113, 0);
    }
}
</style>