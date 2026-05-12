<script setup>
import { ref, computed } from "vue";

import Auth from "./components/BO/auth/auth.vue";
import AdminLogin from "./components/FO/auth/login.vue";

import ApiResponseViewer from "./components/BO/API/ApiResponseViewer.vue";
import Home from "./components/BO/home/Home.vue";
import Import from "./components/BO/import/Import.vue";

import ProductList from "./components/BO/product/ProductList.vue";
import ProductListFO from "./components/FO/product/ProductList.vue";

import ProductCreate from "./components/BO/product/ProductCreate.vue";
import ProductEdit from "./components/BO/product/ProductEdit.vue";

import CustomerList from "./components/BO/customer/CustomerList.vue";
import CustomerCreate from "./components/BO/customer/CustomerCreate.vue";
import CustomerEdit from "./components/BO/customer/CustomerEdit.vue";

import OrderList from "./components/BO/order/OrderList.vue";
import DataResetManager from "./components/BO/reset/DataResetManager.vue";

import { loggedAdmin, adminLogout } from "./utils/auth-state";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

/* ================= MODE ================= */
const mode = ref("BO");

/* ================= PAGE STATE ================= */
const PAGE = {
  FO_PRODUCTS: "products-list-fo",
  FO_LOGIN: "fo-login",

  BO_HOME: "home",
  BO_PRODUCTS: "products-list",
  BO_CREATE: "products-create",
  BO_EDIT: "products-edit",

  BO_CUSTOMERS: "customers-list",
  BO_CUSTOMER_CREATE: "customers-create",
  BO_CUSTOMER_EDIT: "customers-edit",

  BO_ORDERS: "orders-list",
  BO_AUTH: "auth",
  BO_API: "api",
  BO_CSV: "csv-import",
  BO_RESET: "data-reset",
};

const currentPage = ref(PAGE.BO_CSV);

/* ================= DATA ================= */
const selectedProductId = ref(null);
const selectedCustomerId = ref(null);

/* ================= SECURITY ================= */
const isAdmin = computed(() => !!loggedAdmin.value);

/* ================= PRODUCTS ================= */
const openEditProduct = (id) => {
  selectedProductId.value = id;
  currentPage.value = PAGE.BO_EDIT;
};

const closeEditProduct = () => {
  currentPage.value =
    mode.value === "FO" ? PAGE.FO_PRODUCTS : PAGE.BO_PRODUCTS;

  selectedProductId.value = null;
};

/* ================= CUSTOMERS ================= */
const openEditCustomer = (id) => {
  selectedCustomerId.value = id;
  currentPage.value = PAGE.BO_CUSTOMER_EDIT;
};

const closeEditCustomer = () => {
  currentPage.value = PAGE.BO_CUSTOMERS;
  selectedCustomerId.value = null;
};

/* ================= FO LOGIN ================= */
const openFoLogin = () => {
  currentPage.value = PAGE.FO_LOGIN;
};

/* ================= ADMIN ================= */
const handleAdminLogin = () => {
  currentPage.value = PAGE.BO_HOME;
};

const handleAdminLogout = () => {
  adminLogout();
  currentPage.value = PAGE.BO_AUTH;
};

/* ================= NAV FROM HOME ================= */
const handleHomeNavigate = (page) => {
  currentPage.value = page;
};

/* ================= MODE SWITCH ================= */
const switchMode = (newMode) => {
  mode.value = newMode;

  if (newMode === "FO") {
    currentPage.value = PAGE.FO_PRODUCTS;
  }

  if (newMode === "BO") {
    currentPage.value = isAdmin.value ? PAGE.BO_HOME : PAGE.BO_AUTH;
  }
};
</script>

<template>
  <div class="d-flex flex-column vh-100">

    <!-- ================= FO NAVBAR ================= -->
    <nav v-if="mode === 'FO'" class="navbar navbar-dark bg-dark px-3">
      <span class="navbar-brand fw-bold">Shop</span>

      <div class="d-flex gap-2">
        <button class="btn btn-outline-light btn-sm"
                @click="currentPage = 'products-list-fo'">
          Produits
        </button>

        <button class="btn btn-outline-light btn-sm"
                @click="switchMode('BO')">
          Admin
        </button>
      </div>
    </nav>

    <div class="d-flex flex-grow-1">

      <!-- ================= BO SIDEBAR ================= -->
      <div v-if="mode === 'BO'"
           class="bg-dark text-white d-flex flex-column"
           style="width: 260px;">

        <!-- HEADER -->
        <div class="p-3 border-bottom border-secondary">

          <h5 class="text-center mb-3">Admin Panel</h5>

          <button class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2"
                  @click="switchMode('FO')">
            <i class="bi bi-arrow-left-circle"></i>
            Retour boutique
          </button>

        </div>

        <!-- AUTH -->
        <div class="p-3 border-bottom border-secondary">

          <div v-if="!isAdmin">
            <button class="btn btn-primary w-100"
                    @click="currentPage = 'auth'">
              <i class="bi bi-shield-lock me-1"></i>
              Login Admin
            </button>
          </div>

          <div v-else class="d-flex justify-content-between align-items-center">

            <div class="text-truncate">
              <div class="small fw-bold">{{ loggedAdmin.email }}</div>
            </div>

            <button class="btn btn-sm btn-outline-danger"
                    @click="handleAdminLogout">
              <i class="bi bi-box-arrow-right"></i>
            </button>

          </div>
        </div>

        <!-- NAV -->
        <div v-if="isAdmin" class="flex-grow-1 p-2">

          <div class="nav flex-column gap-1">

            <a class="nav-link text-white"
               :class="{ 'active bg-primary': currentPage === PAGE.BO_HOME }"
               @click="currentPage = PAGE.BO_HOME">
              <i class="bi bi-house me-2"></i> Home
            </a>

            <a class="nav-link text-white"
               :class="{ 'active bg-primary': currentPage === PAGE.BO_PRODUCTS }"
               @click="currentPage = PAGE.BO_PRODUCTS">
              <i class="bi bi-box me-2"></i> Products
            </a>

            <a class="nav-link text-white"
               :class="{ 'active bg-primary': currentPage === PAGE.BO_CUSTOMERS }"
               @click="currentPage = PAGE.BO_CUSTOMERS">
              <i class="bi bi-people me-2"></i> Customers
            </a>

            <a class="nav-link text-white"
               :class="{ 'active bg-primary': currentPage === PAGE.BO_ORDERS }"
               @click="currentPage = PAGE.BO_ORDERS">
              <i class="bi bi-receipt me-2"></i> Orders
            </a>

          </div>

        </div>

      </div>

      <!-- ================= MAIN ================= -->
      <div class="flex-grow-1 overflow-auto bg-light p-4">

        <!-- FO -->
        <ProductListFO
          v-if="mode === 'FO' && currentPage === PAGE.FO_PRODUCTS"
          @require-login="openFoLogin"
        />

        <Auth v-if="mode === 'FO' && currentPage === PAGE.FO_LOGIN" />

        <!-- BO -->
        <Home v-if="mode === 'BO' && currentPage === PAGE.BO_HOME"
              @navigate="handleHomeNavigate" />

        <ProductList v-if="mode === 'BO' && currentPage === PAGE.BO_PRODUCTS"
                     @edit="openEditProduct" />

        <ProductCreate v-if="mode === 'BO' && currentPage === PAGE.BO_CREATE"
                       @done="currentPage = PAGE.BO_PRODUCTS" />

        <ProductEdit v-if="mode === 'BO' && currentPage === PAGE.BO_EDIT"
                     :product-id="selectedProductId"
                     @done="closeEditProduct"
                     @cancel="closeEditProduct" />

        <CustomerList v-if="mode === 'BO' && currentPage === PAGE.BO_CUSTOMERS"
                      @edit="openEditCustomer" />

        <CustomerCreate v-if="mode === 'BO' && currentPage === PAGE.BO_CUSTOMER_CREATE"
                        @done="currentPage = PAGE.BO_CUSTOMERS" />

        <CustomerEdit v-if="mode === 'BO' && currentPage === PAGE.BO_CUSTOMER_EDIT"
                      :customer-id="selectedCustomerId"
                      @done="closeEditCustomer" />

        <OrderList v-if="mode === 'BO' && currentPage === PAGE.BO_ORDERS" />

        <AdminLogin v-if="mode === 'BO' && currentPage === PAGE.BO_AUTH"
                    @success="handleAdminLogin" />

        <ApiResponseViewer v-if="mode === 'BO' && currentPage === PAGE.BO_API" />
        <Import v-if="mode === 'BO' && currentPage === PAGE.BO_CSV" />
        <DataResetManager v-if="mode === 'BO' && currentPage === PAGE.BO_RESET" />

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