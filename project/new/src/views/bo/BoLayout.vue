<script setup>
import { computed } from "vue";
import { useRouter, RouterLink, RouterView } from "vue-router";
import { adminLogout, loggedAdmin } from "../../utils/auth-state";

const router = useRouter();
const isAdmin = computed(() => !!loggedAdmin.value);

const handleLogout = () => {
  adminLogout();
  router.push({ name: "bo-login" });
};
</script>

<template>
  <div class="d-flex flex-grow-1">
    <div class="bg-dark text-white" style="width: 260px;">
      <div class="p-3">
        <h4 class="text-center mb-3">Admin Panel</h4>

        <RouterLink class="btn btn-sm btn-outline-light w-100 mb-3" :to="{ name: 'fo-products' }">
          Retour FO
        </RouterLink>

        <div class="mb-3">
          <div v-if="!isAdmin">
            <RouterLink class="btn btn-primary w-100" :to="{ name: 'bo-login' }">
              Login Admin
            </RouterLink>
          </div>

          <div v-else class="text-center">
            <div class="fw-bold mb-1">Admin connecte</div>
            <button class="btn btn-sm btn-danger" @click="handleLogout">
              Logout
            </button>
          </div>
        </div>

        <div v-if="isAdmin" class="nav flex-column">
          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-home' }"
          >
            Home
          </RouterLink>

          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-products' }"
          >
            Products
          </RouterLink>

          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-customers' }"
          >
            Customers
          </RouterLink>

          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-orders' }"
          >
            Orders
          </RouterLink>

          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-api' }"
          >
            API Viewer
          </RouterLink>

          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-csv-import' }"
          >
            CSV Import
          </RouterLink>

          <RouterLink
            class="nav-link text-white"
            active-class="active bg-primary"
            :to="{ name: 'bo-data-reset' }"
          >
            Data Reset
          </RouterLink>
        </div>
      </div>
    </div>

    <div class="flex-grow-1 overflow-auto bg-light p-4">
      <RouterView />
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
