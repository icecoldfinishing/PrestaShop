<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { getXmlText, psGet } from '../../../utils/prestashop-api';

type ProductCard = {
  id: number;
  name: string;
  price: string;
  reference: string;
  imageUrl: string | null;
};

const emit = defineEmits<{
  (e: 'view', id: number): void;
}>();

const products = ref<ProductCard[]>([]);
const loading = ref(false);

const getImageUrl = (productId: number, imageId: string | number | null) => {
  if (!productId || !imageId) return null;
  return `http://localhost:8088/api/images/products/${productId}/${imageId}`;
};

const loadProducts = async () => {
  loading.value = true;
  try {
    const data: any = await psGet('products', '', { display: 'full' });
    const productData = data?.prestashop?.products?.product;
    if (!productData) {
      products.value = [];
      return;
    }

    const items = Array.isArray(productData) ? productData : [productData];
    products.value = items
      .map((p: any) => {
        const nameObj = p.name?.language;
        const name = Array.isArray(nameObj) ? getXmlText(nameObj[0]) : getXmlText(nameObj);

        const id = Number(p.id);
        const images = p.associations?.images?.image;
        let imageId: string | number | null = null;
        if (Array.isArray(images)) {
          imageId = images.length > 0 ? images[0].id : null;
        } else if (images?.id) {
          imageId = images.id;
        }

        if (p.active === null || p.active === undefined) {
          return null;
        }

        return {
          id,
          name,
          price: Number.parseFloat(p.price || 0).toFixed(2),
          reference: p.reference || '',
          imageUrl: getImageUrl(id, imageId),
        } as ProductCard;
      })
      .filter(Boolean);
  } catch (error) {
    console.error('Error fetching products:', error);
  } finally {
    loading.value = false;
  }
};

const openProduct = (id: number) => emit('view', id);

onMounted(() => {
  loadProducts();
});
</script>

<template>
  <section class="home-fo">
    <div class="hero">
      <div class="hero-text">
        <p class="eyebrow">Collection</p>
        <h1>Accueil produits</h1>
        <p class="sub">
          Decouvrez nos produits et ouvrez une fiche pour voir les details.
        </p>
      </div>
      <div class="hero-accent"></div>
    </div>

    <div v-if="loading" class="state">Chargement des produits...</div>

    <div v-else class="grid">
      <article v-for="product in products" :key="product.id" class="card">
        <div class="media">
          <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" />
          <div v-else class="media-fallback">No image</div>
        </div>
        <div class="content">
          <div class="meta">Ref: {{ product.reference || 'N/A' }}</div>
          <h3>{{ product.name }}</h3>
          <div class="price">{{ product.price }} EUR</div>
        </div>
        <div class="actions">
          <button class="btn-outline" @click="openProduct(product.id)">
            Voir fiche
          </button>
        </div>
      </article>
    </div>
  </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

.home-fo {
  --ink: #0e141b;
  --muted: #6b7280;
  --accent: #e95f2b;
  --accent-soft: rgba(233, 95, 43, 0.15);
  --card: #ffffff;
  --border: #e6e8ec;
  font-family: 'Space Grotesk', 'Trebuchet MS', sans-serif;
  padding: 24px;
  background: radial-gradient(circle at top left, #f8f3eb, #f0f5ff 40%, #f9fafb 70%);
  min-height: 100%;
}

.hero {
  position: relative;
  background: #101820;
  color: #f9fafb;
  border-radius: 18px;
  padding: 28px 28px 36px;
  overflow: hidden;
  margin-bottom: 24px;
}

.hero-text h1 {
  font-size: clamp(28px, 3vw, 40px);
  margin: 6px 0 8px;
}

.eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.22em;
  font-size: 12px;
  color: rgba(249, 250, 251, 0.7);
  margin: 0;
}

.sub {
  color: rgba(249, 250, 251, 0.75);
  margin: 0;
  max-width: 520px;
}

.hero-accent {
  position: absolute;
  right: -60px;
  top: -40px;
  width: 220px;
  height: 220px;
  background: radial-gradient(circle, rgba(233, 95, 43, 0.5), transparent 70%);
}

.state {
  color: var(--muted);
  padding: 16px 4px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 18px;
}

.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  display: grid;
  grid-template-rows: auto 1fr auto;
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
  transform: translateY(0);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
}

.media {
  background: #f7f7f7;
  height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.media img {
  max-height: 160px;
  max-width: 100%;
  object-fit: contain;
}

.media-fallback {
  color: var(--muted);
  font-size: 12px;
}

.content {
  padding: 16px;
}

.content h3 {
  font-size: 18px;
  margin: 6px 0 8px;
  color: var(--ink);
}

.meta {
  font-size: 12px;
  color: var(--muted);
}

.price {
  font-size: 18px;
  font-weight: 700;
  color: var(--accent);
}

.actions {
  padding: 0 16px 16px;
}

.btn-outline {
  width: 100%;
  border: 1px solid var(--accent);
  background: transparent;
  color: var(--accent);
  padding: 10px 14px;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s ease, color 0.2s ease;
}

.btn-outline:hover {
  background: var(--accent);
  color: #fff;
}

@media (max-width: 640px) {
  .home-fo {
    padding: 16px;
  }

  .hero {
    padding: 20px;
  }
}
</style>
