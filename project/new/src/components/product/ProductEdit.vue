<script setup lang="ts">
import { ref, watch } from 'vue';
import { getXmlText, psGet, psPut } from '../../utils/prestashop-api';

const props = defineProps<{
    productId: number | null
}>();

const emit = defineEmits<{
    (e: 'done'): void;
    (e: 'cancel'): void;
}>();

const product = ref<any>({
    id: 0,
    name: '',
    reference: '',
    price: '',
    active: true
});

const loading = ref(false);
const saving = ref(false);

const getProduct = async (id: number) => {
    loading.value = true;
    try {
        const data = await psGet('products', id, {
            display: 'full'
        });

        const p = data?.prestashop?.product;
        if (p) {
            const nameObj = p.name?.language;
            const name = Array.isArray(nameObj) ? nameObj[0] : nameObj;

            product.value = {
                id: Number(p.id),
                name: getXmlText(name || p.name),
                reference: p.reference || '',
                price: parseFloat(p.price || 0).toFixed(2),
                active: p.active === '1' || p.active === 1
            };
        }
    } catch (error) {
        console.error('Error fetching product:', error);
    } finally {
        loading.value = false;
    }
};

const updateProduct = async () => {
    if (!product.value.id) return;

    saving.value = true;
    try {
        const xml = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <product>
        <id>${product.value.id}</id>
        <name>
            <language id="1"><![CDATA[${product.value.name}]]></language>
        </name>
        <reference><![CDATA[${product.value.reference}]]></reference>
        <price>${parseFloat(product.value.price)}</price>
        <active>${product.value.active ? '1' : '0'}</active>
    </product>
</prestashop>`;

        await psPut(`products/${product.value.id}`, xml);
        
        alert('Produit mis à jour avec succès !');
        emit('done');
    } catch (error) {
        console.error('Error updating product:', error);
        alert('Erreur lors de la mise à jour du produit');
    } finally {
        saving.value = false;
    }
};

watch(
    () => props.productId,
    (id) => {
        if (typeof id === 'number' && !Number.isNaN(id)) {
            getProduct(id);
        }
    },
    { immediate: true }
);
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Modifier Produit #{{ product.id }}</h2>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-secondary"></div>
        </div>

        <div v-else class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label>Nom du produit</label>
                    <input v-model="product.name" class="form-control" />
                </div>
                <div class="mb-3">
                    <label>Référence</label>
                    <input v-model="product.reference" class="form-control" />
                </div>
                <div class="mb-3">
                    <label>Prix (€)</label>
                    <input 
                        v-model="product.price" 
                        type="number" 
                        step="0.01" 
                        class="form-control" 
                    />
                </div>
                <div class="mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" v-model="product.active" class="form-check-input" />
                        Actif
                    </label>
                </div>

                <button 
                    @click="updateProduct" 
                    class="btn btn-primary me-2" 
                    :disabled="saving"
                >
                    {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
                </button>
                <button @click="emit('cancel')" class="btn btn-secondary">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</template>