<script setup>
import { ref, computed } from "vue";

import Auth from "./components/auth/auth.vue";
import AdminLogin from "./components/auth/login.vue";
import ApiResponseViewer from "./components/API/ApiResponseViewer.vue";
import Home from "./components/home/Home.vue";

import ProductList from "./components/product/ProductList.vue";
import ProductCreate from "./components/product/ProductCreate.vue";
import ProductEdit from "./components/product/ProductEdit.vue";

import CustomerList from "./components/customer/CustomerList.vue";
import CustomerCreate from "./components/customer/CustomerCreate.vue";
import CustomerEdit from "./components/customer/CustomerEdit.vue";

import OrderList from "./components/order/OrderList.vue";
import CSVImportWizard from "./components/import/CSVImportWizard.vue";
import DataResetManager from "./components/reset/DataResetManager.vue";

import { loggedAdmin, adminLogout } from "./utils/auth-state";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

/* ================= MODE ================= */
const mode = ref("BO");

/* ================= PAGE STATE ================= */
const currentPage = ref("auth");

const selectedProductId = ref(null);
const selectedCustomerId = ref(null);

/* ================= COMPUTED SECURITY ================= */
const isAdmin = computed(() => !!loggedAdmin.value);

/* ================= PRODUCTS ================= */
const openEditProduct = (id) => {
  selectedProductId.value = id;
  currentPage.value = "products-edit";
};

const closeEditProduct = () => {
  currentPage.value = "products-list";
  selectedProductId.value = null;
};

/* ================= CUSTOMERS ================= */
const openEditCustomer = (id) => {
  selectedCustomerId.value = id;
  currentPage.value = "customers-edit";
};

const closeEditCustomer = () => {
  currentPage.value = "customers-list";
  selectedCustomerId.value = null;
};

const openFoLogin = () => {
  if (mode.value === "FO") {
    currentPage.value = "fo-login";
  }
};

const handleAdminLogin = () => {
  currentPage.value = "home";
};

const handleAdminLogout = () => {
  adminLogout();
  currentPage.value = "auth";
};

const handleHomeNavigate = (page) => {
  if (page === "api") {
    currentPage.value = "api";
  }

  if (page === "csv-import") {
    currentPage.value = "csv-import";
  }

  if (page === "data-reset") {
    currentPage.value = "data-reset";
  }
};

/* ================= MODE SWITCH ================= */
const switchMode = (newMode) => {
  mode.value = newMode;

  if (newMode === "FO") {
    currentPage.value = "products-list"; // shop par défaut
  }

  if (newMode === "BO") {
    // 🔒 sécurité BO
    if (!isAdmin.value) {
      currentPage.value = "auth";
    } else {
      currentPage.value = "home";
    }
  }
};
</script>

<template>
  <div class="d-flex flex-column vh-100">

    <!-- ================= FO NAVBAR ================= -->
    <nav v-if="mode === 'FO'" class="navbar navbar-dark bg-dark px-3">
      <span class="navbar-brand fw-bold">Shop</span>

      <div class="d-flex gap-2">
        <button class="btn btn-outline-light btn-sm" @click="currentPage = 'products-list'">
          Produits
        </button>

        <button class="btn btn-outline-light btn-sm" @click="switchMode('BO')">
          Admin
        </button>
      </div>
    </nav>

    <div class="d-flex flex-grow-1">

      <!-- ================= BO SIDEBAR ================= -->
      <div v-if="mode === 'BO'" class="bg-dark text-white d-flex flex-column h-100" style="width: 260px;">

        <!-- HEADER -->
        <div class="p-3 border-bottom border-secondary">
          <h5 class="text-center mb-3">Admin Panel</h5>

          <!-- RETURN FO -->
          <button class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2"
            @click="switchMode('FO')">
            <i class="bi bi-arrow-left-circle"></i>
            Retour boutique
          </button>
        </div>

        <!-- AUTH INFO -->
        <div class="p-3 border-bottom border-secondary">
          <div v-if="!isAdmin">
            <button class="btn btn-primary w-100" @click="currentPage = 'auth'">
              <i class="bi bi-shield-lock me-1"></i>
              Login Admin
            </button>
          </div>

          <div v-else class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
              <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                style="width: 34px; height: 34px; flex-shrink: 0;">
                <i class="bi bi-person text-white"></i>
              </div>

              <div class="text-truncate">
                <div class="small fw-bold text-truncate">
                  {{ loggedAdmin.email }}
                </div>
              </div>
            </div>

            <button class="btn btn-sm btn-outline-danger" @click="handleAdminLogout">
              <i class="bi bi-box-arrow-right"></i>
            </button>
          </div>
        </div>

        <!-- NAVIGATION -->
        <div v-if="isAdmin" class="flex-grow-1 overflow-auto p-2">

          <div class="nav flex-column gap-1">

            <a class="nav-link text-white d-flex align-items-center gap-2"
              :class="{ 'active bg-primary': currentPage === 'home' }" @click="currentPage = 'home'">
              <i class="bi bi-house"></i>
              Home
            </a>

            <a class="nav-link text-white d-flex align-items-center gap-2"
              :class="{ 'active bg-primary': currentPage === 'products-list' }" @click="currentPage = 'products-list'">
              <i class="bi bi-box"></i>
              Products
            </a>

            <a class="nav-link text-white d-flex align-items-center gap-2"
              :class="{ 'active bg-primary': currentPage === 'customers-list' }"
              @click="currentPage = 'customers-list'">
              <i class="bi bi-people"></i>
              Customers
            </a>

            <a class="nav-link text-white d-flex align-items-center gap-2"
              :class="{ 'active bg-primary': currentPage === 'orders-list' }" @click="currentPage = 'orders-list'">
              <i class="bi bi-receipt"></i>
              Orders
            </a>

          </div>
        </div>

        <!-- FOOTER ACTIONS -->
        <div v-if="isAdmin" class="p-2 border-top border-secondary small text-center text-muted">
          v1.0 Admin Panel
        </div>

      </div>

      <!-- ================= MAIN ================= -->
      <div class="flex-grow-1 overflow-auto bg-light p-4">

        <!-- FO / BO CONTENT -->

        <ProductList v-if="currentPage === 'products-list'" @edit="openEditProduct" @require-login="openFoLogin" />

        <ProductCreate v-if="mode === 'BO' && currentPage === 'products-create'"
          @done="currentPage = 'products-list'" />

        <ProductEdit v-if="mode === 'BO' && currentPage === 'products-edit'" :product-id="selectedProductId"
          @done="closeEditProduct" @cancel="closeEditProduct" />

        <CustomerList v-if="mode === 'BO' && currentPage === 'customers-list'" @edit="openEditCustomer" />

        <CustomerCreate v-if="mode === 'BO' && currentPage === 'customers-create'"
          @done="currentPage = 'customers-list'" />

        <CustomerEdit v-if="mode === 'BO' && currentPage === 'customers-edit'" :customer-id="selectedCustomerId"
          @done="closeEditCustomer" />

        <OrderList v-if="mode === 'BO' && currentPage === 'orders-list'" />

        <Home v-if="mode === 'BO' && currentPage === 'home'" @navigate="handleHomeNavigate" />

        <Auth v-if="mode === 'FO' && currentPage === 'fo-login'" />

        <AdminLogin v-if="mode === 'BO' && currentPage === 'auth'" @success="handleAdminLogin" />

        <ApiResponseViewer v-if="mode === 'BO' && currentPage === 'api'" />
        <CSVImportWizard v-if="mode === 'BO' && currentPage === 'csv-import'" />
        <DataResetManager v-if="mode === 'BO' && currentPage === 'data-reset'" />

      </div>
    </div>

  </div>
</template>

<style scoped>
.nav-link {
  padding: 10px;
  border-radius: 6px;
  margin: 2px 6px;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
  background: #0d6efd !important;
  color: white !important;
}
</style>