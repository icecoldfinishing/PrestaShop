<script setup>
import { computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import CustomerEdit from "../../components/customer/CustomerEdit.vue";

const route = useRoute();
const router = useRouter();

const customerId = computed(() => Number(route.params.id));

const handleDone = () => {
  router.push({ name: "bo-customers" });
};
</script>

<template>
  <CustomerEdit :customer-id="customerId" @done="handleDone" />
</template>
