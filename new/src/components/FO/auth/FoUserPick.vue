<script setup>
import { ref, onMounted } from 'vue';
import { psGetActiveCustomersBrief, psLoginCustomerWithoutPassword } from '../../../utils/auth/auth-api';
import { setLoggedCustomer } from '../../../utils/auth/auth-state';

/* =========================================
   EMITS
   - choose-login : un compte a été sélectionné → connexion effectuée → aller à FO_HOME
   - browse-anonymous : l'utilisateur veut naviguer sans compte → FO_HOME en anonyme
========================================= */
const emit = defineEmits(['choose-login', 'browse-anonymous']);

const customers = ref([]);
const loading = ref(false);
const errorMsg = ref('');

const load = async () => {
    loading.value = true;
    errorMsg.value = '';
    try {
        const all = await psGetActiveCustomersBrief();
        // Afficher tous les comptes actifs (plus de filtre sur "anonym")
        customers.value = all;
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
        setLoggedCustomer(customer);
        emit('choose-login', email);
    } catch (e) {
        console.error(e);
        errorMsg.value = 'Échec de la connexion.';
    }
};

// Navigation anonyme : retour à la boutique sans créer de session
const browseAnonymous = () => {
    emit('browse-anonymous');
};

onMounted(load);
</script>

<template>
    <div class="pick-wrap py-5">
        <div class="container" style="max-width: 720px">

            <!-- HEADER -->
            <div class="text-center mb-5">
                <div class="icon-wrap mx-auto mb-3">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h1 class="h3 fw-bold mb-2">Connexion</h1>
                <p class="text-muted mb-0">
                    Sélectionnez votre compte pour vous connecter,
                    ou continuez en tant qu'invité.
                </p>
            </div>

            <!-- LOADING -->
            <div v-if="loading" class="text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm me-2" />
                Chargement des comptes…
            </div>

            <!-- ERROR -->
            <div v-else-if="errorMsg" class="alert alert-danger rounded-3">{{ errorMsg }}</div>

            <!-- ACCOUNT LIST -->
            <div v-else>
                <p class="small text-muted text-uppercase fw-semibold mb-2 ps-1" v-if="customers.length">
                    Comptes disponibles
                </p>
                <div class="list-group shadow-sm rounded-3 overflow-hidden mb-4">
                    <button
                        v-for="c in customers"
                        :key="c.id"
                        type="button"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 px-4"
                        @click="loginWithoutPassword(c.email)"
                    >
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle">
                                {{ (c.firstname?.[0] ?? '?').toUpperCase() }}
                            </div>
                            <div class="text-start">
                                <div class="fw-semibold">{{ c.firstname }} {{ c.lastname }}</div>
                                <div class="small text-muted">{{ c.email }}</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>

                    <div v-if="!customers.length" class="list-group-item text-center text-muted py-4">
                        Aucun compte disponible.
                    </div>
                </div>

                <!-- SEPARATOR -->
                <div class="divider-text mb-4">
                    <span>ou</span>
                </div>

                <!-- BROWSE ANONYMOUS -->
                <button
                    type="button"
                    class="btn btn-guest w-100 py-3 rounded-3"
                    @click="browseAnonymous"
                >
                    <i class="bi bi-bag me-2"></i>
                    Continuer sans compte
                    <span class="d-block small fw-normal mt-1 opacity-75">
                        Vous pourrez vous connecter au moment du paiement
                    </span>
                </button>
            </div>

        </div>
    </div>
</template>

<style scoped>
.pick-wrap {
    min-height: calc(100vh - 120px);
}

.icon-wrap {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.avatar-circle {
    width: 38px;
    height: 38px;
    min-width: 38px;
    background: linear-gradient(135deg, #ff6b00, #ff8c42);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.15s ease;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background: #fff8f5;
}

.divider-text {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #adb5bd;
    font-size: 0.85rem;
}

.divider-text::before,
.divider-text::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e9ecef;
}

.btn-guest {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    color: #495057;
    font-weight: 600;
    transition: all 0.2s ease;
    line-height: 1.3;
}

.btn-guest:hover {
    background: #fff8f5;
    border-color: #ff6b00;
    color: #ff6b00;
}
</style>
