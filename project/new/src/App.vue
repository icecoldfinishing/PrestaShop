<script setup>
import { ref } from "vue";

import auth from "./components/auth/auth.vue";
import ApiResponseViewer from "./components/API/ApiResponseViewer.vue";

import ProductList from "./components/product/ProductList.vue";
import ProductCreate from "./components/product/ProductCreate.vue";
import CustomerList from "./components/customer/CustomerList.vue";
import OrderList from "./components/order/OrderList.vue";

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

const selectedCustomerId = ref(null);



const currentPage = ref("products-list");
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
          <li class="nav-item">
            <a
              class="nav-link"
              :class="{ active: currentPage === 'customers-list' }"
              href="#"
              @click.prevent="currentPage = 'customers-list'"
            >
              Customers
            </a>
          </li>

          <!-- ORDERS -->
          <li class="nav-item">
            <a
              class="nav-link"
              :class="{ active: currentPage === 'orders-list' }"
              href="#"
              @click.prevent="currentPage = 'orders-list'"
            >
              Orders
            </a>
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
    <ProductList v-if="currentPage === 'products-list'" />
    <ProductCreate v-if="currentPage === 'products-create'" />

    <CustomerList 
        v-if="currentPage === 'customers-list'"
        @edit="openEditCustomer"
      />    
      
    <OrderList v-if="currentPage === 'orders-list'" />

    <ApiResponseViewer v-if="currentPage === 'api'" />
    <auth v-if="currentPage === 'auth'" />
  </div>
</template>
