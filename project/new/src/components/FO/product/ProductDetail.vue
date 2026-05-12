<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { extractText, psGetProductFullDetails } from '../../../utils/prestashop-api';

// 1. Correction du type : variants doit être un objet Record<nom_du_groupe, liste_de_valeurs>
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

const TAX_RATE = 0.2; 
const props = defineProps<{ productId: number | null }>();
const emit = defineEmits<{ (e: 'back'): void }>();

const product = ref<ProductDetail | null>(null);
const loading = ref(false);

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

        product.value = {
            id: Number(p.id),
            name: extractText(p.name),
            reference: p.reference || '',
            priceHT,
            priceTTC: priceHT * (1 + TAX_RATE),
            description: extractText(p.description) || 'Aucune description',
            imageUrl: getImageUrl(Number(p.id), imageId),
            features: fullData.features,
            // On garde l'objet tel quel car psGetProductFullDetails le renvoie déjà groupé
            variants: fullData.variants, 
        };
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
                <!-- On vérifie si l'objet contient des clés (Taille, Couleur, etc.) -->
                <div class="selectors" v-if="Object.keys(product.variants).length > 0">
                    <div v-for="(values, groupName) in product.variants" :key="groupName" class="select-group">
                        <label>{{ groupName }}</label>
                        <select>
                            <option v-for="val in values" :key="val" :value="val">
                                {{ val }}
                            </option>
                        </select>
                    </div>
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
/* Tes styles restent inchangés, ils sont corrects */
.detail { padding: 24px; font-family: sans-serif; }
.back { background: #111; color: white; padding: 10px; border-radius: 8px; cursor: pointer; border: none; margin-bottom: 20px;}
.layout { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
.media img { max-width: 100%; border-radius: 8px; }
.price { font-size: 24px; font-weight: bold; color: #e85d04; margin-bottom: 20px; }

.selectors {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
.select-group { display: flex; flex-direction: column; }
.select-group label { font-weight: bold; margin-bottom: 5px; color: #555; }
.select-group select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; }

.section { margin-top: 20px; }
.specs ul { list-style: disc; padding-left: 20px; }
.desc { line-height: 1.5; }
</style>