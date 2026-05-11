<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { getXmlText, psGet, psDelete } from '../../../utils/prestashop-api';
import { isLoggedIn } from '../../../utils/auth-state';

const products = ref<any[]>([]);
const loading = ref(false);
const deletingId = ref<number | null>(null);

const emit = defineEmits<{
    (e: 'edit', id: number): void;
    (e: 'require-login'): void;
}>();

/**
 * Génère URL image PrestaShop
 */
const getImageUrl = (productId: number, imageId: any) => {
    if (!productId || !imageId) return null;
    return `http://localhost:8088/api/images/products/${productId}/${imageId}`;
};

const getAllProducts = async () => {
    loading.value = true;
    try {
        const data = await psGet('products', '', {
            display: 'full'
        });

        const productData = data?.prestashop?.products?.product;
        if (!productData) {
            products.value = [];
            return;
        }

        const productsArray = Array.isArray(productData) ? productData : [productData];

        products.value = productsArray.map(p => {
            const nameObj = p.name?.language;
            let name = '';
            if (Array.isArray(nameObj)) {
                name = getXmlText(nameObj[0]);
            } else {
                name = getXmlText(nameObj);
            }

            const id = Number(p.id);
            let imageId = null;
            const images = p.associations?.images?.image;

            if (Array.isArray(images)) {
                imageId = images.length > 0 ? images[0].id : null;
            } else if (images?.id) {
                imageId = images.id;
            }

            return {
                id,
                name,
                price: parseFloat(p.price || 0).toFixed(2),
                reference: p.reference || '',
                active: p.active === '1' || p.active === 1,
                imageUrl: getImageUrl(id, imageId)
            };
        });
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
};

const editProduct = (id: number) => {
    emit('edit', id);
};

const deleteProduct = async (id: number) => {
    if (!window.confirm('Supprimer ce produit ? Cette action est irréversible.')) {
        return;
    }

    deletingId.value = id;
    try {
        await psDelete('products', id);
        await getAllProducts();
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Erreur lors de la suppression du produit');
    } finally {
        deletingId.value = null;
    }
};


onMounted(() => {
    getAllProducts();
});
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Product List</h2>

        <div v-if="products.length" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <div class="col" v-for="product in products" :key="product.id">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Image -->
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <img
                            v-if="product.imageUrl"
                            :src="product.imageUrl"
                            :alt="product.name"
                            class="img-fluid"
                            style="max-height: 180px; object-fit: contain;"
                        />
                        <span v-else class="text-muted">No image</span>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title text-truncate fw-semibold">{{ product.name }}</h5>
                        <p class="text-muted small mb-1">Ref: {{ product.reference || 'N/A' }}</p>
                        <p :style="{ color: product.price > 20 ? 'green' : 'red' }" class="text-primary fw-bold fs-5 mb-2">{{ product.price }} €</p>
                        
                        <span class="badge" :class="product.active ? 'bg-success' : 'bg-danger'">
                            {{ product.active ? 'Active' : 'Inactive' }}
                        </span>

                        <div class="d-grid gap-2 mt-3">
                            <div class="d-flex gap-2">
                                <button 
                                    class="btn btn-sm btn-outline-secondary flex-fill"
                                    @click="editProduct(product.id)"
                                >
                                    Edit
                                </button>
                                <button 
                                    class="btn btn-sm btn-outline-danger flex-fill"
                                    :disabled="deletingId === product.id"
                                    @click="deleteProduct(product.id)"
                                >
                                    {{ deletingId === product.id ? '...' : 'Delete' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="loading" class="text-center py-5">
            <div class="spinner-border text-secondary"></div>
            <p class="mt-2">Loading products...</p>
        </div>
    </div>
</template>