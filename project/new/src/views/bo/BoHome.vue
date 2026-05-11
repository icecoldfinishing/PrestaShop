<script setup>
import { useRouter } from "vue-router";
import Home from "../../components/home/Home.vue";

const router = useRouter();

const handleNavigate = (page) => {
  if (page === "api") {
    router.push({ name: "bo-api" });
  }

  if (page === "csv-import") {
    router.push({ name: "bo-csv-import" });
  }

  if (page === "data-reset") {
    router.push({ name: "bo-data-reset" });
  }
};
</script>

<template>
  <Home @navigate="handleNavigate" />
</template>
