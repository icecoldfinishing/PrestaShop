<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { extractText, psGetProductFullDetails } from '../../../utils/prestashop-api';

type ProductDetail = {
    id: number;
    name: string;
    reference: string;
    priceHT: number;
    priceTTC: number;
    description: string;
    imageUrl: string | null;
    features: string[];
    variants: string[];
};

const TAX_RATE = 0.2; 
const props = defineProps<{ productId: number | null }>();
const emit = defineEmits<{ (e: 'back'): void }>();

const product = ref<ProductDetail | null>(null);
const loading = ref(false);

const getImageUrl = (productId: number, imageId: string | number | null) => {
    if (!productId || !imageId) return null;
    // Vérifie bien ton port et ton domaine PrestaShop
    return `http://localhost:8088/api/images/products/${productId}/${imageId}`;
};

const loadProduct = async (id: number | null) => {
    if (!id) {
        product.value = null;
        return;
    }

    loading.value = true;
    try {
        // On récupère l'objet complet (raw + features + variants)
        const fullData = await psGetProductFullDetails(id);
        const p = fullData.raw;

        // Image
        const images = p.associations?.images?.image;
        let imageId = Array.isArray(images) ? images[0]?.id : images?.id;

        // Prix
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
            variants: fullData.variants
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

        <div v-if="loading" class="state">Chargement de la fiche complète...</div>

        <div v-else-if="product" class="layout">
            <div class="media">
                <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" />
                <div v-else class="media-fallback">Aucune image disponible</div>
            </div>

            <div class="info">
                <p class="meta">Référence : {{ product.reference || 'N/A' }}</p>
                <h2>{{ product.name }}</h2>

                <div class="price">
                    {{ product.priceTTC.toFixed(2) }} €
                    <span class="ht">HT : {{ product.priceHT.toFixed(2) }} €</span>
                </div>

                <hr />

                <!-- DÉCLINAISONS -->
                <div class="section" v-if="product.variants.length > 0">
                    <h3>Options disponibles</h3>
                    <div class="variant-tags">
                        <span v-for="(v, idx) in product.variants" :key="idx" class="tag">{{ v }}</span>
                    </div>
                </div>

                <!-- CARACTÉRISTIQUES -->
                <div class="section" v-if="product.features.length > 0">
                    <h3>Spécifications</h3>
                    <ul class="spec-list">
                        <li v-for="(f, idx) in product.features" :key="idx">{{ f }}</li>
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
.detail { padding: 24px; font-family: sans-serif; max-width: 1200px; margin: 0 auto; }
.back { background: #333; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px; }
.layout { display: grid; grid-template-columns: 450px 1fr; gap: 40px; }
.media img { width: 100%; border-radius: 8px; border: 1px solid #eee; }
.media-fallback { height: 400px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999; }

.price { font-size: 28px; font-weight: bold; color: #d00000; margin: 15px 0; }
.ht { font-size: 14px; color: #777; font-weight: normal; margin-left: 10px; }

.section { margin-top: 25px; }
h3 { font-size: 16px; text-transform: uppercase; color: #555; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px; }

.tag { display: inline-block; background: #f0f0f0; padding: 4px 10px; border-radius: 20px; font-size: 13px; margin-right: 8px; margin-bottom: 8px; border: 1px solid #ddd; }
.spec-list { padding-left: 20px; }
.spec-list li { font-size: 14px; margin-bottom: 5px; color: #444; }
.desc { line-height: 1.6; color: #333; font-size: 15px; }

hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
</style>