<script setup>
import { useRouter } from "vue-router";
import CustomerCreate from "../../components/customer/CustomerCreate.vue";

const router = useRouter();

const handleDone = () => {
  router.push({ name: "bo-customers" });
};
</script>

<template>
  <CustomerCreate @done="handleDone" />
</template>
