<script setup>
import { ref, onMounted } from 'vue';
import { psGetActiveCustomersBrief , psLoginCustomerWithoutPassword } from '../../../utils/auth/auth-api';
import { setLoggedCustomer } from '../../../utils/auth/auth-state';


const emit = defineEmits(['choose-login', 'guest']);

const customers = ref([]);
const loading = ref(false);
const errorMsg = ref('');

const load = async () => {
    loading.value = true;
    errorMsg.value = '';
    try {
        customers.value = await psGetActiveCustomersBrief();
    } catch (e) {
        console.error(e);
        errorMsg.value = 'Impossible de charger les utilisateurs.';
        customers.value = [];
    } finally {
        loading.value = false;
    }
};

const loginWithoutPassword = async (email) => {
    try {
        const customer = await psLoginCustomerWithoutPassword(email);
        customers.value = customers.value.filter(c => c.email !== email);
        setLoggedCustomer(customer);
        emit('choose-login', email);
    } catch (e) {
        console.error(e);
        errorMsg.value = 'Échec de la connexion sans mot de passe.';
    }
};

const goGuest = () => {
    emit('guest');
};

onMounted(load);
</script>

<template>
    <div class="pick-wrap py-5">
        <div class="container" style="max-width: 720px">
            <div class="text-center mb-4">
                <h1 class="h3 fw-bold">Choisir un profil</h1>
                <p class="text-muted mb-0">
                    Sélectionnez un compte pour passer à l’écran de connexion (email prérempli), ou parcourez la boutique en invité.
                </p>
            </div>

            <div v-if="loading" class="text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm me-2" />
                Chargement des comptes…
            </div>

            <div v-else-if="errorMsg" class="alert alert-danger">{{ errorMsg }}</div>

            <div v-else class="list-group shadow-sm rounded-3 overflow-hidden mb-3">
                <button
                    v-for="c in customers"
                    :key="c.id"
                    type="button"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3"
                    @click="loginWithoutPassword(c.email)"
                >
                    <div class="text-start">
                        <div class="fw-semibold">{{ c.firstname }} {{ c.lastname }}</div>
                        <div class="small text-muted">{{ c.email }}</div>
                    </div>
                    <span class="bi bi-chevron-right text-muted" />
                </button>
            </div>

            <button
                type="button"
                class="btn btn-outline-secondary w-100 py-3 rounded-3 mb-2"
                @click="goGuest"
            >
                <i class="bi bi-person-slash me-2" />
                Utilisateur anonyme (navigation sans compte)
            </button>

            <p class="small text-muted text-center mb-0">
                En mode invité, le panier local fonctionne ; la validation de commande nécessite un compte client.
            </p>
        </div>
    </div>
</template>

<style scoped>
.pick-wrap {
    min-height: calc(100vh - 120px);
}
</style>
