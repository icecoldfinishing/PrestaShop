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

          <!-- ==================== UTILS ==================== -->
          <div class="mt-3">
            <div class="text-uppercase text-muted small px-3 mb-2 fw-bold">Utils</div>
            <a href="#" class="nav-link text-white mb-1" :class="{ 'active bg-primary': currentPage === 'api' }"
              @click.prevent="currentPage = 'api'">
              API Response Viewer
            </a>
            <a href="#" class="nav-link text-white mb-1" :class="{ 'active bg-primary': currentPage === 'csv-import' }"
              @click.prevent="currentPage = 'csv-import'">
              CSV Import
            </a>
            <a href="#" class="nav-link text-white" :class="{ 'active bg-primary': currentPage === 'auth' }"
              @click.prevent="currentPage = 'auth'">
              Test Login
            </a>
          </div>

        </div>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 overflow-auto bg-light">
      <div class="p-4">
        <Home v-if="currentPage === 'home'" @navigate="page => currentPage = page" />
        <ProductList v-if="currentPage === 'products-list'" @edit="openEditProduct" />
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