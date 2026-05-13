<script setup>
import { ref } from "vue";
import { setLoggedAdmin } from "../../../utils/auth-state";
import { psLoginAdmin } from "../../../utils/prestashop-api";

const emit = defineEmits(["success"]);

const DEFAULT_ADMIN = {
    email: "rohyamboara@gmail.com",
    password: "rohybapex",
};

const credentials = ref({
    email: DEFAULT_ADMIN.email,
    password: DEFAULT_ADMIN.password,
});

const loading = ref(false);
const errorMsg = ref("");

const handleLogin = async () => {
    loading.value = true;
    errorMsg.value = "";

    try {
        const admin = await psLoginAdmin(credentials.value.email, credentials.value.password);
        setLoggedAdmin(admin);
        emit("success", admin);
    } catch (error) {
        errorMsg.value = error.message || "Erreur lors de la connexion.";
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div class="admin-auth-container d-flex align-items-center justify-content-center py-5">
        <div class="card admin-auth-card shadow-lg border-0 overflow-hidden">

            <div class="row g-0 h-100">

                <!-- LEFT SIDE -->
                <div
                    class="col-md-5 admin-side d-none d-md-flex align-items-center justify-content-center text-white p-5 text-center">
                    <div>
                        <div class="admin-icon-wrapper mb-4">
                            <i class="bi bi-shield-lock-fill display-2"></i>
                        </div>

                        <h2 class="fw-bold mb-3">
                            Admin Panel
                        </h2>

                        <p class="opacity-75 mb-0">
                            Connectez-vous au Back Office PrestaShop pour gérer
                            produits, commandes, clients et imports XML/CSV.
                        </p>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-7 bg-white p-4 p-lg-5">

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-person-gear fs-3 text-primary"></i>
                            <h2 class="fw-bold text-dark mb-0">
                                Back Office Login
                            </h2>
                        </div>

                        <p class="text-muted mb-0">
                            Authentification administrateur
                        </p>
                    </div>

                    <form @submit.prevent="handleLogin">

                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label class="form-label small fw-bold text-uppercase text-muted">
                                Email administrateur
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope-fill text-muted"></i>
                                </span>

                                <input v-model="credentials.email" type="email"
                                    class="form-control bg-light border-start-0" placeholder="admin@prestashop.com"
                                    required />

                            </div>

                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-4">

                            <label class="form-label small fw-bold text-uppercase text-muted">
                                Mot de passe
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-key-fill text-muted"></i>
                                </span>

                                <input v-model="credentials.password" type="password"
                                    class="form-control bg-light border-start-0" placeholder="••••••••" required />

                            </div>

                        </div>

                        <!-- LOGIN BUTTON -->
                        <button type="submit"
                            class="btn btn-dark w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                            :disabled="loading">

                            <span v-if="loading" class="spinner-border spinner-border-sm"></span>

                            <i v-else class="bi bi-box-arrow-in-right"></i>

                            {{ loading ? 'Connexion...' : 'Accéder au Back Office' }}

                        </button>

                    </form>

                    <!-- ERROR -->
                    <div v-if="errorMsg" class="alert alert-danger mt-4 py-2 small d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ errorMsg }}
                    </div>

                    <!-- FOOTER -->
                    <div class="text-center mt-4 small text-muted">
                        PrestaShop Administration Interface
                    </div>

                </div>

            </div>

        </div>
    </div>
</template>


<style scoped>
.admin-auth-container {
    min-height: 100vh;
    background:
        linear-gradient(135deg,
            #f5f7fa 0%,
            #e4ecfb 100%);
}

.admin-auth-card {
    width: 100%;
    max-width: 900px;
    border-radius: 24px;
}

.admin-side {
    background:
        linear-gradient(135deg,
            #0d1117 0%,
            #1f2937 100%);
}

.admin-icon-wrapper {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);

    display: flex;
    align-items: center;
    justify-content: center;

    margin: auto;
}

.input-group-text {
    border: 1px solid #dee2e6;
}

.form-control {
    transition: all 0.2s ease;
}

.form-control:focus {
    background-color: #fff !important;
    box-shadow: none;
    border-color: #212529;
}

.btn-dark {
    border-radius: 12px;
    transition: all 0.25s ease;
}

.btn-dark:hover {
    transform: translateY(-2px);
}

.card {
    backdrop-filter: blur(10px);
}
</style>