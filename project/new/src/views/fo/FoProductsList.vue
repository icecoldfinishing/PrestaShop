<script setup>
import { useRouter } from "vue-router";
import ProductList from "../../components/product/ProductList.vue";

const router = useRouter();

const handleRequireLogin = () => {
  router.push({ name: "fo-login" });
};
</script>

<template>
  <ProductList @require-login="handleRequireLogin" />
</template>
