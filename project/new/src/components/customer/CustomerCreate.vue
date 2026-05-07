<script setup>
import { ref } from 'vue';
import { psPost } from '../../utils/prestashop-api';

const form = ref({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    active: 1,
    id_lang: 1,
    id_default_group: 3
});

const loading = ref(false);
const success = ref(null);
const error = ref(null);

const resetForm = () => {
    form.value = {
        firstname: '',
        lastname: '',
        email: '',
        password: '',
        active: 1,
        id_lang: 1,
        id_default_group: 3
    };
};

const createCustomer = async () => {
    loading.value = true;
    success.value = null;
    error.value = null;

    try {
        const xmlPayload = `
<prestashop>
  <customer>
    <firstname><![CDATA[${form.value.firstname}]]></firstname>
    <lastname><![CDATA[${form.value.lastname}]]></lastname>
    <email><![CDATA[${form.value.email}]]></email>
    <passwd><![CDATA[${form.value.password}]]></passwd>
    <active>${form.value.active}</active>
    <id_lang>${form.value.id_lang}</id_lang>
    <id_default_group>${form.value.id_default_group}</id_default_group>
  </customer>
</prestashop>
        `.trim();

        await psPost('customers', xmlPayload);

        success.value = "Customer created successfully!";
        resetForm();

    } catch (err) {
        console.error(err);
        error.value = err?.message || "Error creating customer";
    } finally {
        loading.value = false;
    }
};
</script>

<template>
<div class="container py-4">
    <h2 class="fw-bold mb-4">Create Customer</h2>

    <!-- SUCCESS -->
    <div v-if="success" class="alert alert-success">
        {{ success }}
    </div>

    <!-- ERROR -->
    <div v-if="error" class="alert alert-danger">
        {{ error }}
    </div>

    <form @submit.prevent="createCustomer" class="card p-4 shadow-sm">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">First Name *</label>
                <input v-model="form.firstname" class="form-control" required />
            </div>

            <div class="col-md-6">
                <label class="form-label">Last Name *</label>
                <input v-model="form.lastname" class="form-control" required />
            </div>

            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input v-model="form.email" type="email" class="form-control" required />
            </div>

            <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input v-model="form.password" type="password" class="form-control" required />
            </div>

            <div class="col-md-4">
                <label class="form-label">Active</label>
                <select v-model="form.active" class="form-select">
                    <option :value="1">Yes</option>
                    <option :value="0">No</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Lang ID</label>
                <input v-model="form.id_lang" type="number" class="form-control" />
            </div>

            <div class="col-md-4">
                <label class="form-label">Default Group</label>
                <input v-model="form.id_default_group" type="number" class="form-control" />
            </div>

        </div>

        <button class="btn btn-primary mt-4 w-100" :disabled="loading">
            <span v-if="loading">Creating...</span>
            <span v-else>Create Customer</span>
        </button>

    </form>
</div>
</template>