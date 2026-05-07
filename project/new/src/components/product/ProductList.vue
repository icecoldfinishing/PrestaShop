<script setup>
import { ref, onMounted } from 'vue';
import { getXmlText, psGet } from '../../utils/prestashop-api';

const products = ref([]);

/**
 * Génère URL image PrestaShop
 */
const getImageUrl = (productId, imageId) => {
    if (!productId || !imageId) return null;

    return `http://localhost:8088/api/images/products/${productId}/${imageId}`;
};

const getAllProduct = async () => {
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
            const name = Array.isArray(nameObj) ? nameObj[0] : nameObj;

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
                name: getXmlText(name || p.name),
                price: parseFloat(p.price || 0).toFixed(2),
                reference: p.reference || '',
                active: p.active === '1' || p.active === 1,
                imageUrl: getImageUrl(id, imageId)
            };
        });

    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

onMounted(() => {
    getAllProduct();
});
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Product List</h2>

        <div v-if="products.length" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

            <div class="col" v-for="product in products" :key="product.id">

                <div class="card h-100 shadow-sm border-0">

                    <!-- IMAGE -->
                    <div class="bg-light d-flex align-items-center justify-content-center"
                            style="height: 200px;">

                        <img
                            v-if="product.imageUrl"
                            :src="product.imageUrl"
                            :alt="product.name"
                            class="img-fluid"
                            style="max-height: 180px; object-fit: contain;"
                        />

                        <span v-else class="text-muted">
                            No image
                        </span>

                    </div>

                    <div class="card-body">

                        <h5 class="card-title text-truncate fw-semibold">
                            {{ product.name }}
                        </h5>

                        <p class="text-muted small mb-1">
                            Ref: {{ product.reference || 'N/A' }}
                        </p>

                        <p class="text-primary fw-bold fs-5 mb-2">
                            {{ product.price }} €
                        </p>

                        <span class="badge" :class="product.active ? 'bg-success' : 'bg-danger'">
                            {{ product.active ? 'Active' : 'Inactive' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

        <div v-else class="text-center text-muted py-5">
            <div class="spinner-border spinner-border-sm me-2"></div>
            Loading products...
        </div>
    </div>
</template>