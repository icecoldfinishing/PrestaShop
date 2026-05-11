<script setup lang="ts">
import { ref, watch } from 'vue';
import { getXmlText, psGet, psPut } from '../../../utils/prestashop-api';

const props = defineProps<{ customerId: number | null }>();
const emit = defineEmits<{ (e: 'done'): void; (e: 'cancel'): void }>();

const customer = ref<any>({
    id: 0,
    firstname: '',
    lastname: '',
    email: '',
    active: true
});

const loading = ref(false);
const saving = ref(false);

const getCustomer = async (id: number) => {
    loading.value = true;
    try {
        const data = await psGet('customers', id, {
            display: '[id,firstname,lastname,email,active]'
        });

        const c = data?.prestashop?.customer;
        if (c) {
            customer.value = {
                id: Number(c.id),
                firstname: getXmlText(c.firstname),
                lastname: getXmlText(c.lastname),
                email: getXmlText(c.email),
                active: c.active === '1'
            };
        }
    } catch (error) {
        console.error('Error fetching customer:', error);
    } finally {
        loading.value = false;
    }
};

const updateCustomer = async () => {
    if (!customer.value.id) {
        return;
    }

    saving.value = true;
    try {
        const xml = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <customer>
        <id>${customer.value.id}</id>
        <firstname><![CDATA[${customer.value.firstname}]]></firstname>
        <lastname><![CDATA[${customer.value.lastname}]]></lastname>
        <email><![CDATA[${customer.value.email}]]></email>
        <active>${customer.value.active ? '1' : '0'}</active>
    </customer>
</prestashop>`;

        await psPut(`customers/${customer.value.id}`, xml);

        alert('Client mis à jour avec succès !');
        emit('done');
    } catch (error) {
        console.error('Error updating customer:', error);
        alert('Erreur lors de la mise à jour');
    } finally {
        saving.value = false;
    }
};

watch(
    () => props.customerId,
    (id) => {
        if (typeof id === 'number' && !Number.isNaN(id)) {
            getCustomer(id);
        }
    },
    { immediate: true }
);
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Modifier Client #{{ customer.id }}</h2>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-secondary"></div>
        </div>

        <div v-else class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label>Prénom</label>
                    <input v-model="customer.firstname" class="form-control" />
                </div>
                <div class="mb-3">
                    <label>Nom</label>
                    <input v-model="customer.lastname" class="form-control" />
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input v-model="customer.email" type="email" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" v-model="customer.active" class="form-check-input" />
                        Actif
                    </label>
                </div>

                <button @click="updateCustomer" class="btn btn-primary me-2" :disabled="saving">
                    {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
                </button>
                <button @click="emit('cancel')" class="btn btn-secondary">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</template>