<script setup>
import { ref } from 'vue';
import { psLoginCustomer } from '../../../utils/prestashop-api';
import { setLoggedCustomer } from '../../../utils/auth-state';

const emit = defineEmits(["success"]);

const credentials = ref({
    email: 'rohy@gmail.com',
    password: 'rohybapex'
});

const loading = ref(false);
const errorMsg = ref('');

const handleLogin = async () => {
    loading.value = true;
    errorMsg.value = '';

    try {
        const customer = await psLoginCustomer(
            credentials.value.email,
            credentials.value.password
        );

        setLoggedCustomer(customer);

        // ✅ NOTIFY parent (App.vue)
        emit("success");

    } catch (error) {
        errorMsg.value = error.message || 'Erreur lors de la connexion.';
    } finally {
        loading.value = false;
    }
};
</script>
<template>
    <div class="auth-container d-flex align-items-center justify-content-center py-5">
        <div class="card auth-card shadow-lg border-0 overflow-hidden">
            <div class="row g-0 h-100">
                <!-- Decoration side -->
                <div
                    class="col-md-5 bg-primary d-none d-md-flex align-items-center justify-content-center text-white p-4 text-center">
                    <div>
                        <i class="bi bi-shield-lock display-1 mb-4"></i>
                        <h3 class="fw-bold">Bienvenue</h3>
                        <p class="opacity-75">Connectez-vous pour accéder à votre boutique PrestaShop.</p>
                    </div>
                </div>

                <!-- Form side -->
                <div class="col-md-7 bg-white p-4 p-lg-5">
                    <div class="mb-4">
                        <h2 class="fw-bold text-dark">Connexion</h2>
                        <p class="text-muted">Entrez vos identifiants ci-dessous</p>
                    </div>

                    <form @submit.prevent="handleLogin">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input v-model="credentials.email" type="email"
                                    class="form-control bg-light border-start-0" placeholder="exemple@mail.com"
                                    required />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-key text-muted"></i>
                                </span>
                                <input v-model="credentials.password" type="password"
                                    class="form-control bg-light border-start-0" placeholder="••••••••" required />
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                            :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                            <i v-else class="bi bi-box-arrow-in-right"></i>
                            {{ loading ? 'Connexion...' : 'Se connecter' }}
                        </button>
                    </form>

                    <div v-if="errorMsg" class="alert alert-danger mt-3 py-2 small d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ errorMsg }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



<style scoped>
.auth-container {
    min-height: calc(100vh - 100px);
}

.auth-card {
    width: 100%;
    max-width: 800px;
    border-radius: 20px;
}

.input-group-text {
    border: 1px solid #dee2e6;
}

.form-control:focus {
    background-color: #fff !important;
    box-shadow: none;
    border-color: #0d6efd;
}

.form-control:focus+.input-group-text {
    border-color: #0d6efd;
}

.btn-primary {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3) !important;
}
</style>