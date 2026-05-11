<script setup>
import { useRouter } from "vue-router";
import CustomerList from "../../components/customer/CustomerList.vue";

const router = useRouter();

const handleEdit = (id) => {
  router.push({ name: "bo-customers-edit", params: { id } });
};
</script>

<template>
  <CustomerList @edit="handleEdit" />
</template>
