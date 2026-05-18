<script setup>
import { ref, computed, watch } from "vue";

import AdminLogin from "./components/BO/auth/auth.vue";
import CustomerLogin from "./components/FO/auth/login.vue";
import FoUserPick from "./components/FO/auth/FoUserPick.vue";

import Home from "./components/BO/home/Home.vue";
import Import from "./components/BO/import/Import.vue";
import HomeFO from "./components/FO/home/Home.vue";

import ProductList from "./components/BO/product/ProductList.vue";
import Stat from "./components/BO/stat/Stat.vue";
import ProductDetailFO from "./components/FO/product/ProductDetail.vue";
import Cart from "./components/FO/cart/Cart.vue";
import FoOrders from "./components/FO/order/OrderList.vue";

import OrderList from "./components/BO/order/OrderList.vue";
import StockManager from "./components/BO/stock/StockManager.vue";
import DataResetManager from "./components/BO/reset/DataResetManager.vue";

import { loggedCustomer, logout, loggedAdmin, adminLogout } from "./utils/auth/auth-state";
import { cart } from "./utils/products/product-api";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

/* ================= MODE ================= */
const mode = ref("FO");

/* ================= PAGE STATE ================= */
const PAGE = {
  FO_HOME: "fo-home",
  FO_USER_PICK: "fo-user-pick",
  FO_PRODUCTS: "products-list-fo",
  FO_PRODUCT_DETAIL: "fo-product-detail",
  FO_LOGIN: "fo-login",
  FO_CART: "fo-cart",
  FO_ORDERS: "fo-orders",

  BO_HOME: "home",
  BO_PRODUCTS: "products-list",
  BO_STOCKS: "stocks-list",
  BO_STAT: "stats",

  BO_ORDERS: "orders-list",
  BO_AUTH: "auth",
  BO_API: "api",
  BO_CSV: "csv-import",
  BO_RESET: "data-reset",
};

const currentPage = ref(PAGE.FO_USER_PICK);
const loginPrefillEmail = ref("");
const foOrdersCount = ref(0);

/* ================= DATA ================= */
const selectedProductId = ref(null);
const selectedCustomerId = ref(null);
const selectedFoProductId = ref(null);

/* ================= SECURITY ================= */
const isAdmin = computed(() => !!loggedAdmin.value);
const isCustomer = computed(() => !!loggedCustomer.value);

cart.setOwner(loggedCustomer.value?.id || null);

import { psGet } from "./utils/prestashop-api";

const fetchFoOrdersCount = async (customerId) => {
  if (!customerId) {
    foOrdersCount.value = 0;
    return;
  }
  try {
    const data = await psGet('orders', '', {
      'filter[id_customer]': `[${customerId}]`,
      display: '[id]'
    });
    const raw = data?.prestashop?.orders?.order;
    const list = Array.isArray(raw) ? raw : raw ? [raw] : [];
    foOrdersCount.value = list.length;
  } catch (e) {
    console.error("Error fetching order count", e);
    foOrdersCount.value = 0;
  }
};

watch(
  () => loggedCustomer.value?.id || null,
  async (nextId) => {
    await cart.setOwner(nextId);
    await fetchFoOrdersCount(nextId);
  },
  { immediate: true }
);



/* ================= FO ================= */
const openFoLogin = () => {
  currentPage.value = PAGE.FO_USER_PICK;
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
  // Panier accessible anonymement — l'auth sera demandée uniquement au checkout
  currentPage.value = PAGE.FO_CART;
};

const goToFoOrders = () => {
  if (!loggedCustomer.value) {
    currentPage.value = PAGE.FO_USER_PICK;
    return;
  }
  if (loggedCustomer.value?.guest) {
    loginPrefillEmail.value = "";
    currentPage.value = PAGE.FO_LOGIN;
    return;
  }
  currentPage.value = PAGE.FO_ORDERS;
};

const goToHomeFO = () => {
  // Accueil accessible à tous, connecté ou non
  currentPage.value = PAGE.FO_HOME;
};

const handleCustomerLogout = () => {
  logout();
  cart.setOwner(null);
  foOrdersCount.value = 0;
  currentPage.value = PAGE.FO_USER_PICK;
};

/* ================= LOGIN SUCCESS ================= */
const handleFoLogin = () => {
  loginPrefillEmail.value = "";
  cart.setOwner(loggedCustomer.value?.id || null);
  fetchFoOrdersCount(loggedCustomer.value?.id);
  currentPage.value = PAGE.FO_HOME;
};

// Navigation anonyme : aucune session créée, loggedCustomer reste null
const handleBrowseAnonymous = () => {
  // Pas de setLoggedCustomer — l'utilisateur navigue sans compte
  cart.setOwner(null);
  foOrdersCount.value = 0;
  loginPrefillEmail.value = "";
  currentPage.value = PAGE.FO_HOME;
};

const onChooseFoLogin = (email) => {
  loginPrefillEmail.value = email;
  cart.setOwner(loggedCustomer.value?.id || null);
  fetchFoOrdersCount(loggedCustomer.value?.id);
  currentPage.value = PAGE.FO_HOME;
};

const backToFoUserPick = () => {
  currentPage.value = PAGE.FO_USER_PICK;
  loginPrefillEmail.value = "";
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
    // Toujours aller à la boutique, connecté ou non
    currentPage.value = PAGE.FO_USER_PICK;
  }

  if (newMode === "BO") {
    currentPage.value = isAdmin.value ? PAGE.BO_HOME : PAGE.BO_AUTH;
  }
};
</script>

<template>
  <div class="d-flex flex-column vh-100">

    <!-- ================= FO NAV ================= -->
    <nav v-if="mode === 'FO'" class="navbar navbar-dark bg-dark px-3 fo-navbar"> <span
        class="navbar-brand fw-bold">Shop</span>

      <div class="d-flex gap-2">
        <button class="btn btn-outline-light btn-sm" @click="goToHomeFO">
          <i class="bi bi-house-door me-1"></i>
          Accueil
        </button>

        <button class="btn btn-outline-light btn-sm position-relative" @click="goToCart">
          <i class="bi bi-cart3 me-1"></i>
          Panier
          <span v-if="cart.count > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ cart.count }}
          </span>
        </button>

        <button class="btn btn-outline-light btn-sm position-relative" @click="goToFoOrders">
          <i class="bi bi-bag-check me-1"></i>
          Mes commandes
          <span v-if="foOrdersCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
            {{ foOrdersCount }}
          </span>
        </button>

        <button class="btn btn-light btn-sm" @click="switchMode('BO')">
          <i class="bi bi-shield-lock me-1"></i>
          Admin
        </button>
      </div>
    </nav>

    <div class="d-flex flex-grow-1 overflow-hidden">

      <!-- ================= BO SIDEBAR ================= -->
      <div v-if="mode === 'BO'" class="bg-dark text-white d-flex flex-column bo-sidebar">
        <div class="p-3 border-bottom border-secondary text-center">
          <div class="mb-2">
            <i class="bi bi-speedometer2 display-6"></i>
          </div>
          <h5 class="mb-3">Admin Panel</h5>

          <button class="btn btn-sm btn-outline-light w-100" @click="switchMode('FO')">
            <i class="bi bi-shop me-1"></i>
            Retour boutique
          </button>
        </div>

        <div class="p-3 border-bottom border-secondary">
          <div v-if="!isAdmin">
            <button class="btn btn-primary w-100" @click="currentPage = PAGE.BO_AUTH">
              Login Admin
            </button>
          </div>

          <div v-else class="d-flex justify-content-between align-items-center">
            <div class="small text-truncate me-2">{{ loggedAdmin.email }}</div><button class="btn btn-sm btn-danger"
              @click="handleAdminLogout">
              <i class="bi bi-box-arrow-right"></i>
            </button>
          </div>
        </div>

        <div v-if="isAdmin" class="p-2 overflow-y-auto">
          <a class="nav-link text-white" :class="{ 'bg-primary': currentPage === PAGE.BO_HOME }"
            @click="currentPage = PAGE.BO_HOME">
            Home
          </a>

          <a class="nav-link text-white" :class="{ 'bg-primary': currentPage === PAGE.BO_PRODUCTS }"
            @click="currentPage = PAGE.BO_PRODUCTS">
            Products
          </a>
          <a class="nav-link text-white" :class="{ 'bg-primary': currentPage === PAGE.BO_STOCKS }"
            @click="currentPage = PAGE.BO_STOCKS">
           Stocks
          </a>
          <a class="nav-link text-white" :class="{ 'bg-primary': currentPage === PAGE.BO_ORDERS }"
            @click="currentPage = PAGE.BO_ORDERS">
            Orders
            <!-- Pas encore de count admin ici, mais on pourrait -->
          </a>
          <a class="nav-link text-white" :class="{ 'bg-primary': currentPage === PAGE.BO_STAT }"
            @click="currentPage = PAGE.BO_STAT">
            Stats
          </a>
        </div>
      </div>

      <!-- ================= MAIN ================= -->
      <div class="main-content p-4 bg-light" :class="{ 'fo-mode': mode === 'FO', 'bo-mode': mode === 'BO' }">

        <!-- ================= FO CHOIX UTILISATEUR ================= -->
        <FoUserPick v-if="mode === 'FO' && currentPage === PAGE.FO_USER_PICK" @choose-login="onChooseFoLogin"
          @browse-anonymous="handleBrowseAnonymous" />

        <!-- ================= FO LOGIN ================= -->
        <CustomerLogin v-if="mode === 'FO' && currentPage === PAGE.FO_LOGIN" :prefill-email="loginPrefillEmail"
          @success="handleFoLogin" @back="backToFoUserPick" />

        <!-- ================= FO BARRE SESSION ================= -->
          <!-- Barre session : afficher si sur une page FO (hors pick/login) -->
          <div
            v-if="mode === 'FO' && currentPage !== PAGE.FO_USER_PICK && currentPage !== PAGE.FO_LOGIN"
            class="alert d-flex justify-content-between align-items-center border mb-3 py-2"
            :class="!loggedCustomer ? 'alert-warning text-dark' : 'bg-body-secondary text-dark'">
            <div class="d-flex align-items-center gap-2">
              <template v-if="!loggedCustomer">
                <i class="bi bi-person-slash me-1"></i>
                <span><b>Mode invité</b> — <span class="small">Connectez-vous pour finaliser votre commande</span></span>
              </template>
              <template v-else>
                <i class="bi bi-person-check-fill me-1 text-success"></i>
                Connecté : <b>{{ loggedCustomer.firstname + ' ' + loggedCustomer.lastname }}</b>
              </template>
            </div>
            <div class="d-flex gap-2">
              <button v-if="!loggedCustomer" class="btn btn-sm btn-dark" @click="currentPage = PAGE.FO_USER_PICK">
                <i class="bi bi-box-arrow-in-right me-1"></i>Se connecter
              </button>
              <button v-else class="btn btn-sm btn-danger" @click="handleCustomerLogout">
                <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
              </button>
            </div>
          </div>


        <!-- ================= FO ================= -->
        <!-- Navigation anonyme : FO_HOME et FO_PRODUCT_DETAIL accessibles sans compte -->
        <HomeFO v-if="mode === 'FO' && currentPage === PAGE.FO_HOME" @view="openFoProduct" />

        <ProductDetailFO v-if="mode === 'FO' && currentPage === PAGE.FO_PRODUCT_DETAIL"
          :product-id="selectedFoProductId" @back="closeFoProduct" @go-to-cart="goToCart" />

        <!-- Panier : accessible anonymement, checkout nécessite connexion -->
        <Cart v-if="mode === 'FO' && currentPage === PAGE.FO_CART"
          @continueShopping="goToHomeFO"
          @request-login="currentPage = PAGE.FO_USER_PICK" />

        <!-- Mes commandes : réservé aux comptes connectés -->
        <FoOrders v-if="mode === 'FO' && currentPage === PAGE.FO_ORDERS && isCustomer" />

        <!-- ================= BO ================= -->
        <Home v-if="mode === 'BO' && currentPage === PAGE.BO_HOME" @navigate="handleHomeNavigate" />

        <ProductList v-if="mode === 'BO' && currentPage === PAGE.BO_PRODUCTS" @edit="openEditProduct" />

        <StockManager v-if="mode === 'BO' && currentPage === PAGE.BO_STOCKS" />
        <Stat v-if="mode === 'BO' && currentPage === PAGE.BO_STAT" />
        <OrderList v-if="mode === 'BO' && currentPage === PAGE.BO_ORDERS" />

        <AdminLogin v-if="mode === 'BO' && currentPage === PAGE.BO_AUTH" @success="handleAdminLogin" />

        <Import v-if="mode === 'BO' && currentPage === PAGE.BO_CSV" />
        <DataResetManager v-if="mode === 'BO' && currentPage === PAGE.BO_RESET" />

      </div>
    </div>
  </div>
</template>

<style scoped>
.fo-navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 56px;
  z-index: 1050;
  display: flex;
  align-items: center;
}

.bo-sidebar {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 240px;
  z-index: 1040;
}

.main-content {
  flex-grow: 1;
  min-height: 100%;
  overflow-y: auto;
}

.main-content.fo-mode {
  padding-top: 76px !important; /* 56px navbar + padding */
}

.main-content.bo-mode {
  margin-left: 240px;
}

.nav-link {
  padding: 10px;
  border-radius: 6px;
  margin: 2px 6px;
  cursor: pointer;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.1);
}
</style>