<script setup>
import { ref } from "vue";
import auth from "./components/auth/auth.vue";
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
import { loggedCustomer, logout } from "./utils/auth-state";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

const currentPage = ref("home");
const selectedProductId = ref(null);
const selectedCustomerId = ref(null);

// PRODUCTS
const openEditProduct = (id) => {
  selectedProductId.value = id;
  currentPage.value = "products-edit";
};
const closeEditProduct = () => {
  currentPage.value = "products-list";
  selectedProductId.value = null;
};

// CUSTOMERS
const openEditCustomer = (id) => {
  selectedCustomerId.value = id;
  currentPage.value = "customers-edit";
};
const closeEditCustomer = () => {
  currentPage.value = "customers-list";
  selectedCustomerId.value = null;
};
</script>

<template>
  <div class="d-flex vh-100">
    <!-- SIDEBAR -->
    <div class="bg-dark text-white" style="width: 260px; overflow-y: auto;">
      <div class="p-3">
        <h4 class="text-center mb-4 text-light">PrestaShop Manager</h4>

        <!-- AUTH SECTION -->
        <div class="mb-4">
          <div v-if="!loggedCustomer" class="px-2">
            <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" 
                    @click="currentPage = 'auth'">
              <i class="bi bi-person-circle"></i>
              Se connecter
            </button>
          </div>
          <div v-else class="card bg-secondary bg-opacity-25 border-0 text-white">
            <div class="card-body p-2 d-flex align-items-center gap-2">
              <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">
                {{ loggedCustomer.firstname?.[0] }}{{ loggedCustomer.lastname?.[0] }}
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="small fw-bold text-truncate">{{ loggedCustomer.firstname }} {{ loggedCustomer.lastname }}</div>
                <button class="btn btn-link btn-sm p-0 text-danger text-decoration-none border-0" @click="logout" style="font-size: 0.75rem;">
                  Déconnexion
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="nav flex-column">
          <!-- Home -->

          <div class="mb-2">
            <button class="btn btn-dark w-100 text-start d-flex justify-content-between align-items-center"
              data-bs-toggle="collapse" data-bs-target="#homeMenu" aria-expanded="true" @click="currentPage = 'home'">
              <span class="fw-bold">Home</span>
            </button>
          </div>
          <!-- ==================== PRODUCTS DROPDOWN ==================== -->
          <div class="mb-2">
            <button class="btn btn-dark w-100 text-start d-flex justify-content-between align-items-center"
              data-bs-toggle="collapse" data-bs-target="#productsMenu" aria-expanded="true">
              <span class="fw-bold">Products</span>
              <i class="bi bi-chevron-down"></i>
            </button>
            <div class="collapse show" id="productsMenu">
              <div class="mt-1">
                <a href="#" class="nav-link text-white"
                  :class="{ 'active bg-primary': currentPage === 'products-list' || currentPage === 'products-edit' }"
                  @click.prevent="currentPage = 'products-list'">
                  Liste des produits
                </a>
                <a href="#" class="nav-link text-white"
                  :class="{ 'active bg-primary': currentPage === 'products-create' }"
                  @click.prevent="currentPage = 'products-create'">
                  Créer un produit
                </a>
              </div>
            </div>
          </div>

          <!-- ==================== CUSTOMERS DROPDOWN ==================== -->
          <div class="mb-2">
            <button class="btn btn-dark w-100 text-start d-flex justify-content-between align-items-center"
              data-bs-toggle="collapse" data-bs-target="#customersMenu">
              <span class="fw-bold">Customers</span>
              <i class="bi bi-chevron-down"></i>
            </button>
            <div class="collapse" id="customersMenu">
              <div class="mt-1">
                <a href="#" class="nav-link text-white"
                  :class="{ 'active bg-primary': currentPage === 'customers-list' || currentPage === 'customers-edit' }"
                  @click.prevent="currentPage = 'customers-list'">
                  Liste des clients
                </a>
                <a href="#" class="nav-link text-white"
                  :class="{ 'active bg-primary': currentPage === 'customers-create' }"
                  @click.prevent="currentPage = 'customers-create'">
                  Créer un client
                </a>
              </div>
            </div>
          </div>

          <!-- ==================== ORDERS ==================== -->
          <div class="mb-2">
            <button class="btn btn-dark w-100 text-start d-flex justify-content-between align-items-center"
              data-bs-toggle="collapse" data-bs-target="#ordersMenu">
              <span class="fw-bold">Orders</span>
              <i class="bi bi-chevron-down"></i>
            </button>
            <div class="collapse" id="ordersMenu">
              <div class="mt-1">
                <a href="#" class="nav-link text-white" :class="{ 'active bg-primary': currentPage === 'orders-list' }"
                  @click.prevent="currentPage = 'orders-list'">
                  Liste des commandes
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 overflow-auto bg-light">
      <div class="p-4">
        <Home v-if="currentPage === 'home'" @navigate="page => currentPage = page" />
        <ProductList v-if="currentPage === 'products-list'" @edit="openEditProduct" @require-login="currentPage = 'auth'" />
        <ProductCreate v-if="currentPage === 'products-create'" @done="currentPage = 'products-list'"
          @cancel="currentPage = 'products-list'" />
        <ProductEdit v-if="currentPage === 'products-edit'" :product-id="selectedProductId" @done="closeEditProduct"
          @cancel="closeEditProduct" />

        <CustomerList v-if="currentPage === 'customers-list'" @edit="openEditCustomer" />
        <CustomerCreate v-if="currentPage === 'customers-create'" @done="currentPage = 'customers-list'"
          @cancel="currentPage = 'customers-list'" />
        <CustomerEdit v-if="currentPage === 'customers-edit'" :customer-id="selectedCustomerId"
          @done="closeEditCustomer" @cancel="closeEditCustomer" />

        <OrderList v-if="currentPage === 'orders-list'" />
        <ApiResponseViewer v-if="currentPage === 'api'" />
        <CSVImportWizard v-if="currentPage === 'csv-import'" />
        <DataResetManager v-if="currentPage === 'data-reset'" />
        <auth v-if="currentPage === 'auth'" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.nav-link {
  padding: 10px 16px;
  border-radius: 6px;
  margin: 2px 8px;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
  background-color: #0d6efd !important;
  color: white !important;
}

.btn-dark {
  border: none;
}
</style>