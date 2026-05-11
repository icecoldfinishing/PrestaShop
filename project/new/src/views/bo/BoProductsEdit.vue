<script setup>
import { computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import ProductEdit from "../../components/product/ProductEdit.vue";

const route = useRoute();
const router = useRouter();

const productId = computed(() => Number(route.params.id));

const handleDone = () => {
  router.push({ name: "bo-products" });
};
</script>

<template>
  <ProductEdit
    :product-id="productId"
    @done="handleDone"
    @cancel="handleDone"
  />
</template>
