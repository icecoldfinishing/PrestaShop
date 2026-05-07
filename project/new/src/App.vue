<script setup>
import { ref } from "vue";

import auth from "./components/auth/auth.vue";
import ApiResponseViewer from "./components/API/ApiResponseViewer.vue";

import ProductList from "./components/product/ProductList.vue";
import ProductCreate from "./components/product/ProductCreate.vue";
import ProductEdit from "./components/product/ProductEdit.vue";

import CustomerCreate from "./components/customer/CustomerCreate.vue";
import CustomerList from "./components/customer/CustomerList.vue";
import CustomerEdit from "./components/customer/CustomerEdit.vue";
import OrderList from "./components/order/OrderList.vue";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

const currentPage = ref("products-list");

const selectedProductId = ref(null);
const openEditProduct = (id) => {
  selectedProductId.value = id;
  currentPage.value = "products-edit";
};
const closeEditProduct = () => {
  currentPage.value = "products-list";
  selectedProductId.value = null;
};

const selectedCustomerId = ref(null);
const openEditCustomer = (id) => {
  selectedCustomerId.value = id;
  currentPage.value = "customers-edit";
};
const closeEditCustomer = () => {
  currentPage.value = "customers-list";
  selectedCustomerId.value = null;
};
</script>

<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a
        class="navbar-brand"
        href="#"
        @click.prevent="currentPage = 'products-list'"
      >
        Navbar
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNavDropdown"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <!-- PRODUCTS -->
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              data-bs-toggle="dropdown"
            >
              Products
            </a>

            <ul class="dropdown-menu">
              <li>
                <a
                  class="dropdown-item"
                  :class="{ active: currentPage === 'products-list' }"
                  href="#"
                  @click.prevent="currentPage = 'products-list'"
                >
                  List
                </a>
              </li>

              <li>
                <a
                  class="dropdown-item"
                  href="#"
                  @click.prevent="currentPage = 'products-create'"
                >
                  Create
                </a>
              </li>
            </ul>
          </li>

          <!-- CUSTOMERS -->
            <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              data-bs-toggle="dropdown"
            >
              Customers
            </a>

            <ul class="dropdown-menu">
              <li>
                <a
                  class="dropdown-item"
                  :class="{ active: currentPage === 'customers-list' }"
                  href="#"
                  @click.prevent="currentPage = 'customers-list'"
                >
                  List
                </a>
              </li>

              <li>
                <a
                  class="dropdown-item"
                  href="#"
                  @click.prevent="currentPage = 'customers-create'"
                >
                  Create
                </a>
              </li>
            </ul>
          </li>

          <!-- UTILS -->
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              data-bs-toggle="dropdown"
            >
              Utils
            </a>

            <ul class="dropdown-menu">
              <li>
                <a
                  class="dropdown-item"
                  href="#"
                  @click.prevent="currentPage = 'api'"
                >
                  Api Response
                </a>
              </li>

              <li>
                <a
                  class="dropdown-item"
                  href="#"
                  @click.prevent="currentPage = 'auth'"
                >
                  Test Login
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <ProductList v-if="currentPage === 'products-list'" @edit="openEditProduct" />
    <ProductCreate 
        v-if="currentPage === 'products-create'"
        @done="currentPage = 'products-list'"
        @cancel="currentPage = 'products-list'"
    />
    <ProductEdit
      v-if="currentPage === 'products-edit'"
      :product-id="selectedProductId"
      @done="closeEditProduct"
      @cancel="closeEditProduct"
    />

    <CustomerCreate 
        v-if="currentPage === 'customers-create'"
        @back="currentPage = 'customers-list'"
    />
    <CustomerList 
        v-if="currentPage === 'customers-list'"
        @edit="openEditCustomer"
      />    
    <CustomerEdit
      v-if="currentPage === 'customers-edit'"
      :customer-id="selectedCustomerId"
      @done="closeEditCustomer"
      @cancel="closeEditCustomer"
    />
      
    <OrderList v-if="currentPage === 'orders-list'" />

    <ApiResponseViewer v-if="currentPage === 'api'" />
    <auth v-if="currentPage === 'auth'" />
  </div>
</template>
