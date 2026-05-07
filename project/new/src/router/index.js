import { createRouter, createWebHistory } from "vue-router";

import CustomerList from "../components/customer/CustomerList.vue";
import CustomerEdit from "../components/customer/CustomerEdit.vue";

const routes = [
    {
        path: "/customers",
        name: "customers",
        component: CustomerList,
    },
    {
        path: "/customers/edit/:id",
        name: "customer-edit",
        component: CustomerEdit,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
