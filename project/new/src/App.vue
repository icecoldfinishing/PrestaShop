<script setup>
import { ref, computed } from "vue";

import AdminLogin from "./components/BO/auth/auth.vue";
import CustomerLogin from "./components/FO/auth/login.vue";

import ApiResponseViewer from "./components/BO/API/ApiResponseViewer.vue";
import Home from "./components/BO/home/Home.vue";
import Import from "./components/BO/import/Import.vue";
import HomeFO from "./components/FO/home/Home.vue";

import ProductList from "./components/BO/product/ProductList.vue";
import ProductDetailFO from "./components/FO/product/ProductDetail.vue";
import Cart from "./components/FO/cart/Cart.vue";
import FoOrders from "./components/FO/order/OrderList.vue";

import ProductCreate from "./components/BO/product/ProductCreate.vue";
import ProductEdit from "./components/BO/product/ProductEdit.vue";

import CustomerList from "./components/BO/customer/CustomerList.vue";
import CustomerCreate from "./components/BO/customer/CustomerCreate.vue";
import CustomerEdit from "./components/BO/customer/CustomerEdit.vue";

import OrderList from "./components/BO/order/OrderList.vue";
import DataResetManager from "./components/BO/reset/DataResetManager.vue";

import { loggedCustomer, logout, loggedAdmin, adminLogout } from "./utils/auth-state";
import { cart } from "./utils/prestashop-api";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

/* ================= MODE ================= */
const mode = ref("FO");

/* ================= PAGE STATE ================= */
const PAGE = {
  FO_HOME: "fo-home",
  FO_PRODUCTS: "products-list-fo",
  FO_PRODUCT_DETAIL: "fo-product-detail",
  FO_LOGIN: "fo-login",
  FO_CART: "fo-cart",
  FO_ORDERS: "fo-orders",

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

const currentPage = ref(PAGE.FO_LOGIN);

/* ================= DATA ================= */
const selectedProductId = ref(null);
const selectedCustomerId = ref(null);
const selectedFoProductId = ref(null);

/* ================= SECURITY ================= */
const isAdmin = computed(() => !!loggedAdmin.value);
const isCustomer = computed(() => !!loggedCustomer.value);

cart.setOwner(loggedCustomer.value?.id || null);

/* ================= GUARD FO ================= */
const requireCustomer = (nextPage) => {
  if (!isCustomer.value) {
    currentPage.value = PAGE.FO_LOGIN;
    return;
  }
  currentPage.value = nextPage;
};

/* ================= PRODUCTS ================= */
const openEditProduct = (id) => {
  selectedProductId.value = id;
  currentPage.value = PAGE.BO_EDIT;
};

const closeEditProduct = () => {
  currentPage.value = mode.value === "FO" ? PAGE.FO_PRODUCTS : PAGE.BO_PRODUCTS;
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

/* ================= FO ================= */
const openFoLogin = () => {
  currentPage.value = PAGE.FO_LOGIN;
};

const openFoProduct = (id) => {
  selectedFoProductId.value = id;
  currentPage.value = PAGE.FO_PRODUCT_DETAIL;
};

const closeFoProduct = () => {
  currentPage.value = PAGE.FO_HOME;
  selectedFoProductId.value = null;
};

const goToCart = () => {
  requireCustomer(PAGE.FO_CART);
};

const goToFoOrders = () => {
  requireCustomer(PAGE.FO_ORDERS);
};

const goToHomeFO = () => {
  requireCustomer(PAGE.FO_HOME);
};

const handleCustomerLogout = () => {
  logout();
  cart.setOwner(null);
  currentPage.value = PAGE.FO_LOGIN;
};

/* ================= LOGIN SUCCESS ================= */
const handleFoLogin = () => {
  cart.setOwner(loggedCustomer.value?.id || null);
  currentPage.value = PAGE.FO_HOME;
};

const handleAdminLogin = () => {
  currentPage.value = PAGE.BO_HOME;
};

const handleCustomerLogin = () => {
  currentPage.value = PAGE.FO_HOME;
};

const handleAdminLogout = () => {
  adminLogout();
  currentPage.value = PAGE.BO_AUTH;
};

/* ================= NAV ================= */
const handleHomeNavigate = (page) => {
  currentPage.value = page;
};

/* ================= MODE ================= */
const switchMode = (newMode) => {
  mode.value = newMode;

  if (newMode === "FO") {
    currentPage.value = PAGE.FO_HOME;
  }

  if (newMode === "BO") {
    currentPage.value = isAdmin.value ? PAGE.BO_HOME : PAGE.BO_AUTH;
  }
};
</script>

<template>
  <div class="d-flex flex-column vh-100">

    <!-- ================= FO NAV ================= -->
    <nav v-if="mode === 'FO'" class="navbar navbar-dark bg-dark px-3">
      <span class="navbar-brand fw-bold">Shop</span>

      <div class="d-flex gap-2">
        <button class="btn btn-outline-light btn-sm"
                @click="goToHomeFO">
          Accueil
        </button>

        <button class="btn btn-outline-light btn-sm"
                @click="goToCart">
          Panier
        </button>

        <button class="btn btn-outline-light btn-sm"
                @click="goToFoOrders">
          Mes commandes
        </button>

        <button class="btn btn-light btn-sm"
                @click="switchMode('BO')">
          Admin
        </button>
      </div>
    </nav>

    <div class="d-flex flex-grow-1">

      <!-- ================= BO SIDEBAR ================= -->
      <div v-if="mode === 'BO'" class="bg-dark text-white d-flex flex-column" style="width: 260px;">

        <div class="p-3 border-bottom border-secondary">
          <h5 class="text-center mb-3">Admin Panel</h5>

          <button class="btn btn-sm btn-outline-light w-100"
                  @click="switchMode('FO')">
            Retour boutique
          </button>
        </div>

        <div class="p-3 border-bottom border-secondary">
          <div v-if="!isAdmin">
            <button class="btn btn-primary w-100"
                    @click="currentPage = PAGE.BO_AUTH">
              Login Admin
            </button>
          </div>

          <div v-else class="d-flex justify-content-between">
            <div class="small">{{ loggedAdmin.email }}</div>
            <button class="btn btn-sm btn-danger"
                    @click="handleAdminLogout">X</button>
          </div>
        </div>

        <div v-if="isAdmin" class="p-2">
          <a class="nav-link text-white"
             :class="{ 'bg-primary': currentPage === PAGE.BO_HOME }"
             @click="currentPage = PAGE.BO_HOME">Home</a>

          <a class="nav-link text-white"
             :class="{ 'bg-primary': currentPage === PAGE.BO_PRODUCTS }"
             @click="currentPage = PAGE.BO_PRODUCTS">Products</a>

          <a class="nav-link text-white"
             :class="{ 'bg-primary': currentPage === PAGE.BO_CUSTOMERS }"
             @click="currentPage = PAGE.BO_CUSTOMERS">Customers</a>

          <a class="nav-link text-white"
             :class="{ 'bg-primary': currentPage === PAGE.BO_ORDERS }"
             @click="currentPage = PAGE.BO_ORDERS">Orders</a>
        </div>
      </div>

      <!-- ================= MAIN ================= -->
      <div class="flex-grow-1 p-4 bg-light">

        <!-- ================= FO LOGIN ================= -->
        <CustomerLogin
          v-if="mode === 'FO' && currentPage === PAGE.FO_LOGIN"
          @success="handleFoLogin"
        />

        <!-- ================= FO AdminLogin STATUS ================= -->
        <div v-if="mode === 'FO' && isCustomer"
             class="alert alert-success d-flex justify-content-between">

          <div>
            Connecté : <b>{{ loggedCustomer.firstname }} {{ loggedCustomer.lastname }}</b>
          </div>

          <button class="btn btn-sm btn-danger" @click="handleCustomerLogout">Logout</button>
        </div>

        <!-- ================= FO ================= -->
        <HomeFO
          v-if="mode === 'FO' && currentPage === PAGE.FO_HOME && isCustomer"
          @view="openFoProduct"
        />

        <ProductDetailFO
          v-if="mode === 'FO' && currentPage === PAGE.FO_PRODUCT_DETAIL && isCustomer"
          :product-id="selectedFoProductId"
          @back="closeFoProduct"
          @go-to-cart="goToCart"
        />

        <Cart
          v-if="mode === 'FO' && currentPage === PAGE.FO_CART && isCustomer"
          @continueShopping="goToHomeFO"
        />

        <FoOrders
          v-if="mode === 'FO' && currentPage === PAGE.FO_ORDERS && isCustomer"
        />

        <!-- ================= BO ================= -->
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
  cursor: pointer;
}

.nav-link:hover {
  background: rgba(255,255,255,0.1);
}
</style>