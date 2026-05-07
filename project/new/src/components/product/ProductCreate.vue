<script setup lang="ts">
import { ref } from 'vue';
import { psPost } from '../../utils/prestashop-api';

const emit = defineEmits<{
    (e: 'done'): void;
    (e: 'cancel'): void;
}>();

const product = ref({
    name: '',
    reference: '',
    price: '',
    active: true
});

const saving = ref(false);
const errorMessage = ref('');

const createProduct = async () => {
    errorMessage.value = '';

    if (!product.value.name || !product.value.price) {
        errorMessage.value = "Le nom et le prix sont obligatoires";
        return;
    }

    saving.value = true;

    try {
        const xml = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <product>
        <name>
            <language id="1"><![CDATA[${product.value.name}]]></language>
        </name>
        <reference><![CDATA[${product.value.reference}]]></reference>
        <price>${parseFloat(product.value.price)}</price>
        <active>${product.value.active ? '1' : '0'}</active>
        <id_category_default>2</id_category_default>
        <id_default_category>2</id_default_category>
        <state>1</state>
        <minimal_quantity>1</minimal_quantity>
    </product>
</prestashop>`;

        await psPost('products', xml);

        alert('Produit créé avec succès !');
        resetForm();
        emit('done');

    } catch (error: any) {
        console.error('Error creating product:', error);
        errorMessage.value = "Erreur lors de la création du produit";
    } finally {
        saving.value = false;
    }
};

const resetForm = () => {
    product.value = {
        name: '',
        reference: '',
        price: '',
        active: true
    };
};
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Créer un Nouveau Produit</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                        <input v-model="product.name" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Référence</label>
                        <input v-model="product.reference" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prix (€) <span class="text-danger">*</span></label>
                        <input 
                            v-model="product.price" 
                            type="number" 
                            step="0.01" 
                            class="form-control" 
                        />
                    </div>
                    <div class="col-12">
                        <label class="form-check-label">
                            <input type="checkbox" v-model="product.active" class="form-check-input" />
                            Produit actif
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <button 
                        @click="createProduct" 
                        class="btn btn-primary me-2" 
                        :disabled="saving"
                    >
                        {{ saving ? 'Création en cours...' : 'Créer le Produit' }}
                    </button>
                    <button @click="emit('cancel')" class="btn btn-secondary">
                        Annuler
                    </button>
                </div>

                <div v-if="errorMessage" class="alert alert-danger mt-3">
                    {{ errorMessage }}
                </div>
            </div>
        </div>
    </div>
</template>