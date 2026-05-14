<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import {
    getXmlText,
    psGet,
    PS_PUBLIC_ORIGIN,
    psGetCategoriesBrief,
    foProductBadgeFromAvailability,
    psGetProductTaxMultiplier,
} from '../../../utils/prestashop-api';

type ProductBadge = 'HOT' | 'NEW' | null;

type ProductCard = {
    id: number;
    name: string;
    price: number;
    priceLabel: string;
    reference: string;
    imageUrl: string | null;
    idCategoryDefault: string;
    categoryName: string;
    availableDate: string;
    dateAdd: string;
    badge: ProductBadge;
};

const emit = defineEmits<{
    (e: 'view', id: number): void;
}>();

const products = ref<ProductCard[]>([]);
const loading = ref(false);

const filterName = ref('');
const filterCategoryId = ref('');
const filterPriceMin = ref<string>('');
const filterPriceMax = ref<string>('');

const categoryOptions = ref<{ id: string; name: string }[]>([]);

const getImageUrl = (productId: number, imageId: string | number | null) => {
    if (!productId || !imageId) return null;
    return `${PS_PUBLIC_ORIGIN}/api/images/products/${productId}/${imageId}`;
};

const isProductActive = (p: Record<string, unknown>) => {
    const raw = p.active;
    if (raw === null || raw === undefined) return true;
    const v = getXmlText(raw as never);
    if (v === '0') return false;
    if (raw === 0 || raw === false) return false;
    return true;
};

const loadCategories = async () => {
    try {
        categoryOptions.value = await psGetCategoriesBrief();
    } catch (e) {
        console.error(e);
        categoryOptions.value = [];
    }
};

const loadProducts = async () => {
    loading.value = true;

    try {
        const catList = await psGetCategoriesBrief();
        const catMap = new Map(catList.map((c) => [c.id, c.name]));

        const data: unknown = await psGet('products', '', { display: 'full' });

        const root = data as {
            prestashop?: {
                products?: {
                    product?: unknown;
                };
            };
        };

        const productData = root?.prestashop?.products?.product;

        if (!productData) {
            products.value = [];
            return;
        }

        const items = Array.isArray(productData)
            ? productData
            : [productData];

        products.value = (
            await Promise.all(
                items.map(async (raw) => {
                    const p = raw as Record<string, unknown>;

                    if (!isProductActive(p)) return null;

                    const nameObj = p.name as { language?: unknown } | undefined;
                    const lang = nameObj?.language;

                    const name = Array.isArray(lang)
                        ? getXmlText(lang[0] as never)
                        : getXmlText(lang as never);

                    const id = Number(p.id);

                    const images = (
                        p.associations as {
                            images?: { image?: unknown };
                        } | undefined
                    )?.images?.image;

                    let imageId: string | number | null = null;

                    if (Array.isArray(images)) {
                        imageId =
                            images.length > 0
                                ? (images[0] as { id?: unknown }).id as string | number
                                : null;
                    } else if (
                        images &&
                        typeof images === 'object' &&
                        'id' in images
                    ) {
                        imageId = (images as { id: string | number }).id;
                    }

                    // =========================
                    // PRIX TTC
                    // =========================

                    const priceHT =
                        Number.parseFloat(String(p.price ?? 0)) || 0;

                    const taxMultiplier =
                        await psGetProductTaxMultiplier(p);

                    const priceTTC = priceHT * taxMultiplier;

                    // =========================

                    const idCat =
                        getXmlText(p.id_category_default as never) || '';

                    const availableDate =
                        getXmlText(p.available_date as never);

                    const dateAdd =
                        getXmlText(p.date_add as never);

                    const badge =
                        foProductBadgeFromAvailability(
                            availableDate,
                            dateAdd
                        ) as ProductBadge;

                    return {
                        id,
                        name,
                        price: priceTTC,
                        priceLabel: priceTTC.toFixed(2),
                        reference: String(p.reference ?? ''),
                        imageUrl: getImageUrl(id, imageId),
                        idCategoryDefault: idCat,
                        categoryName: idCat
                            ? catMap.get(idCat) || `Cat. ${idCat}`
                            : '—',
                        availableDate,
                        dateAdd,
                        badge,
                    } as ProductCard;
                })
            )
        ).filter(Boolean) as ProductCard[];

    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
};

const filteredProducts = computed(() => {
    const nameQ = filterName.value.trim().toLowerCase();
    const catId = filterCategoryId.value;
    const minP = filterPriceMin.value.trim() === '' ? null : Number.parseFloat(filterPriceMin.value);
    const maxP = filterPriceMax.value.trim() === '' ? null : Number.parseFloat(filterPriceMax.value);

    return products.value.filter((p) => {
        if (nameQ && !p.name.toLowerCase().includes(nameQ)) return false;
        if (catId && p.idCategoryDefault !== catId) return false;
        if (minP !== null && Number.isFinite(minP) && p.price < minP) return false;
        if (maxP !== null && Number.isFinite(maxP) && p.price > maxP) return false;
        return true;
    });
});

const openProduct = (id: number) => emit('view', id);

const resetFilters = () => {
    filterName.value = '';
    filterCategoryId.value = '';
    filterPriceMin.value = '';
    filterPriceMax.value = '';
};

onMounted(async () => {
    await loadCategories();
    await loadProducts();
});
</script>

<template>
    <section class="home-fo">
        <div class="hero">
            <div class="hero-text">
                <p class="eyebrow">Boutique</p>
                <h1>Catalogue produits</h1>
                <p class="sub">
                    Filtrez par nom, catégorie et prix. Les pastilles HOT / NEW suivent la date de disponibilité
                    (<code>available_date</code>) ou la date de création du produit.
                </p>
            </div>
            <div class="hero-accent" />
        </div>

        <div class="filters card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Nom</label>
                        <input v-model="filterName" type="search" class="form-control" placeholder="Rechercher…"
                            autocomplete="off" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Catégorie</label>
                        <select v-model="filterCategoryId" class="form-select">
                            <option value="">Toutes</option>
                            <option v-for="c in categoryOptions" :key="c.id" :value="c.id">
                                {{ c.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Prix min (€)</label>
                        <input v-model="filterPriceMin" type="number" min="0" step="0.01" class="form-control"
                            placeholder="0" />
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Prix max (€)</label>
                        <input v-model="filterPriceMax" type="number" min="0" step="0.01" class="form-control"
                            placeholder="∞" />
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-secondary w-100" @click="resetFilters">
                            Reset
                        </button>
                    </div>
                </div>
                <p class="small text-muted mb-0 mt-2">
                    {{ filteredProducts.length }} produit(s) sur {{ products.length }}
                </p>
            </div>
        </div>

        <div v-if="loading" class="state">Chargement des produits…</div>

        <div v-else class="grid">
            <article v-for="product in filteredProducts" :key="product.id" class="card">
                <div class="media">
                    <span v-if="product.badge" class="badge-float" :class="product.badge === 'HOT' ? 'hot' : 'new'">
                        {{ product.badge }}
                    </span>
                    <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" />
                    <div v-else class="media-fallback">No image</div>
                </div>
                <div class="content">
                    <div class="meta">Ref: {{ product.reference || 'N/A' }}</div>
                    <div class="cat small text-muted">{{ product.categoryName }}</div>
                    <h3>{{ product.name }}</h3>
                    <div class="price">{{ product.priceLabel }} €</div>
                </div>
                <div class="actions">
                    <button type="button" class="btn-outline" @click="openProduct(product.id)">
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
    max-width: 640px;
    font-size: 14px;
}

.sub code {
    color: #fecaca;
    font-size: 12px;
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
    position: relative;
    background: #f7f7f7;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge-float {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.06em;
    padding: 4px 8px;
    border-radius: 8px;
    color: #fff;
}

.badge-float.hot {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
}

.badge-float.new {
    background: linear-gradient(135deg, #22c55e, #15803d);
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

.cat {
    margin-bottom: 4px;
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
