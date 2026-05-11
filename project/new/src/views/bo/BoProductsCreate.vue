<script setup>
import { useRouter } from "vue-router";
import ProductCreate from "../../components/product/ProductCreate.vue";

const router = useRouter();

const handleDone = () => {
  router.push({ name: "bo-products" });
};
</script>

<template>
  <ProductCreate @done="handleDone" />
</template>
