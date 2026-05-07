<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { psGet, psPut, getXmlText } from '../../utils/prestashop-api';

const props = defineProps<{
    id: number // Reçu depuis App.vue
}>();

const emit = defineEmits(['close']); // Pour revenir à la liste

const loading = ref(false);
const saving = ref(false);
const error = ref<string | null>(null);
const success = ref<string | null>(null);

const form = ref({
    id: props.id,
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    active: 1,
    id_lang: 1,
    id_default_group: 3
});

const loadCustomer = async () => {
    loading.value = true;
    try {
        const data = await psGet(`customers/${props.id}`, '');
        const c = data?.prestashop?.customer;
        if (!c) throw new Error("Customer not found");

        form.value = {
            id: Number(c.id),
            firstname: getXmlText(c.firstname),
            lastname: getXmlText(c.lastname),
            email: c.email,
            password: '',
            active: Number(c.active),
            id_lang: Number(c.id_lang || 1),
            id_default_group: Number(c.id_default_group || 3)
        };
    } catch (err: any) {
        error.value = err.message;
    } finally {
        loading.value = false;
    }
};

const updateCustomer = async () => {
    saving.value = true;
    try {
        const xmlPayload = `
        <prestashop>
          <customer>
            <id>${form.value.id}</id>
            <firstname><![CDATA[${form.value.firstname}]]></firstname>
            <lastname><![CDATA[${form.value.lastname}]]></lastname>
            <email><![CDATA[${form.value.email}]]></email>
            ${form.value.password ? `<passwd><![CDATA[${form.value.password}]]></passwd>` : ''}
            <active>${form.value.active}</active>
            <id_lang>${form.value.id_lang}</id_lang>
            <id_default_group>${form.value.id_default_group}</id_default_group>
          </customer>
        </prestashop>`.trim();

        await psPut(`customers/${form.value.id}`, xmlPayload);
        success.value = "Customer updated successfully";
        setTimeout(() => emit('close'), 1500); // Retour à la liste après 1.5s
    } catch (err: any) {
        error.value = err.message;
    } finally {
        saving.value = false;
    }
};

onMounted(loadCustomer);
</script>

<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Edit Customer #{{ id }}</h2>
            <button class="btn btn-secondary" @click="emit('close')">Back to List</button>
        </div>

        <div v-if="error" class="alert alert-danger">{{ error }}</div>
        <div v-if="success" class="alert alert-success">{{ success }}</div>

        <form v-if="!loading" @submit.prevent="updateCustomer" class="card p-4 shadow-sm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input v-model="form.firstname" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input v-model="form.lastname" class="form-control" required />
                </div>
                <div class="col-md-12">
                    <label class="form-label">Email</label>
                    <input v-model="form.email" type="email" class="form-control" required />
                </div>
            </div>
            <button class="btn btn-warning mt-4 w-100" :disabled="saving">
                {{ saving ? 'Saving...' : 'Update Customer' }}
            </button>
        </form>
    </div>
</template>