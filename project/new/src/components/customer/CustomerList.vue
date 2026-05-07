<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { getXmlText, psDelete, psGet } from '../../utils/prestashop-api';

const emit = defineEmits<{ (e: 'edit', id: number): void }>();

const customers = ref<any[]>([]);
const loading = ref(false);
const deletingId = ref<number | null>(null);

const getAllCustomers = async () => {
    loading.value = true;
    try {
        const data = await psGet('customers', '', {
            display: '[id,firstname,lastname,email,active]',
        });

        const customerData = data?.prestashop?.customers?.customer;
        if (!customerData) {
            customers.value = [];
            return;
        }

        const customersArray = Array.isArray(customerData) ? customerData : [customerData];

        customers.value = customersArray.map(c => ({
            id: Number(c.id),
            firstname: getXmlText(c.firstname),
            lastname: getXmlText(c.lastname),
            email: getXmlText(c.email),
            active: c.active === '1'
        }));
    } catch (error) {
        console.error('Error fetching customers:', error);
    } finally {
        loading.value = false;
    }
};

// Fonction pour aller vers la page d'édition
const goToEdit = (id: number) => {
    emit('edit', id);
};

const deleteCustomer = async (id: number) => {
    if (!window.confirm('Supprimer ce client ?')) {
        return;
    }

    deletingId.value = id;
    try {
        await psDelete('customers', id);
        await getAllCustomers();
    } catch (error) {
        console.error('Error deleting customer:', error);
        alert('Erreur lors de la suppression');
    } finally {
        deletingId.value = null;
    }
};

onMounted(() => {
    getAllCustomers();
});
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Customer List</h2>

        <div v-if="customers.length" class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col" v-for="customer in customers" :key="customer.id">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-semibold text-dark">
                            {{ customer.firstname }} {{ customer.lastname }}
                        </h5>
                        <p class="text-muted small">{{ customer.email }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge" :class="customer.active ? 'bg-success' : 'bg-danger'">
                                {{ customer.active ? 'Active' : 'Inactive' }}
                            </span>
                            
                            <div class="d-flex gap-2">
                                <button
                                    class="btn btn-sm btn-primary"
                                    @click="goToEdit(customer.id)"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    :disabled="deletingId === customer.id"
                                    @click="deleteCustomer(customer.id)"
                                >
                                    {{ deletingId === customer.id ? 'Suppression...' : 'Delete' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="loading" class="text-center py-5">
            <div class="spinner-border text-secondary"></div>
        </div>

        <div v-else>
            <p class="text-muted">Aucun client trouvé.</p>
        </div>
    </div>
</template>