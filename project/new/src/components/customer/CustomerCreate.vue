<script setup lang="ts">
import { ref } from 'vue';
import { psPost } from '../../utils/prestashop-api';

const emit = defineEmits(['cancel']);

const customer = ref({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    active: true
});

const saving = ref(false);
const errorMessage = ref('');

const createCustomer = async () => {
    errorMessage.value = '';

    if (!customer.value.firstname || !customer.value.lastname || 
        !customer.value.email || !customer.value.password) {
        errorMessage.value = "Prénom, Nom, Email et Mot de passe sont obligatoires";
        return;
    }

    saving.value = true;

    try {
        const xml = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <customer>
        <firstname><![CDATA[${customer.value.firstname}]]></firstname>
        <lastname><![CDATA[${customer.value.lastname}]]></lastname>
        <email><![CDATA[${customer.value.email}]]></email>
        <passwd><![CDATA[${customer.value.password}]]></passwd>
        <active>${customer.value.active ? '1' : '0'}</active>
        <id_gender>1</id_gender>
        <id_default_group>3</id_default_group>
    </customer>
</prestashop>`;

        await psPost('customers', xml);
        
        alert("Client créé avec succès");
        resetForm();
        emit('cancel');

    } catch (error: any) {
        console.error(error);
        errorMessage.value = error.response?.data?.prestashop?.errors?.error?.message 
                         || "Erreur lors de la création du client";
    } finally {
        saving.value = false;
    }
};

const resetForm = () => {
    customer.value.firstname = '';
    customer.value.lastname = '';
    customer.value.email = '';
    customer.value.password = '';
    customer.value.active = true;
};
</script>

<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Créer un Nouveau Client</h2>
            <button class="btn btn-secondary" @click="emit('cancel')">
                Retour à la liste
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input v-model="customer.firstname" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input v-model="customer.lastname" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input v-model="customer.email" type="email" class="form-control" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input v-model="customer.password" type="password" class="form-control" />
                    </div>
                    <div class="col-12">
                        <input type="checkbox" v-model="customer.active" class="form-check-input me-2" />
                        <label class="form-check-label">Client actif</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button 
                        class="btn btn-primary me-3"
                        @click="createCustomer"
                        :disabled="saving"
                    >
                        {{ saving ? 'Création en cours...' : 'Créer le Client' }}
                    </button>
                    <button class="btn btn-secondary" @click="emit('cancel')">
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