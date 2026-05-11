<script setup>
import { useRouter } from "vue-router";
import ProductList from "../../components/product/ProductList.vue";

const router = useRouter();

const handleEdit = (id) => {
  router.push({ name: "bo-products-edit", params: { id } });
};
</script>

<template>
  <ProductList @edit="handleEdit" />
</template>
