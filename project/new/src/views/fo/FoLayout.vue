<script setup>
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
  <div class="d-flex flex-column vh-100">
    <nav class="navbar navbar-dark bg-dark px-3">
      <span class="navbar-brand fw-bold">Shop</span>

      <div class="d-flex gap-2">
        <RouterLink class="btn btn-outline-light btn-sm" :to="{ name: 'fo-products' }">
          Produits
        </RouterLink>

        <RouterLink class="btn btn-outline-light btn-sm" :to="{ name: 'bo-home' }">
          Admin
        </RouterLink>
      </div>
    </nav>

    <div class="flex-grow-1 overflow-auto bg-light p-4">
      <RouterView />
    </div>
  </div>
</template>
