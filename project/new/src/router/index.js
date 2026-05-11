import { createRouter, createWebHistory } from "vue-router";
import { isAdminLoggedIn } from "../utils/auth-state";

import FoLayout from "../views/fo/FoLayout.vue";
import FoProductsList from "../views/fo/FoProductsList.vue";
import FoAuth from "../views/fo/FoAuth.vue";

import BoLayout from "../views/bo/BoLayout.vue";
import BoLogin from "../views/bo/BoLogin.vue";
import BoHome from "../views/bo/BoHome.vue";
import BoProductsList from "../views/bo/BoProductsList.vue";
import BoProductsCreate from "../views/bo/BoProductsCreate.vue";
import BoProductsEdit from "../views/bo/BoProductsEdit.vue";
import BoCustomersList from "../views/bo/BoCustomersList.vue";
import BoCustomersCreate from "../views/bo/BoCustomersCreate.vue";
import BoCustomersEdit from "../views/bo/BoCustomersEdit.vue";
import BoOrdersList from "../views/bo/BoOrdersList.vue";
import BoApiResponseViewer from "../views/bo/BoApiResponseViewer.vue";
import BoCsvImport from "../views/bo/BoCsvImport.vue";
import BoDataReset from "../views/bo/BoDataReset.vue";

const routes = [
    {
        path: "/",
        component: FoLayout,
        children: [
            {
                path: "",
                name: "fo-products",
                component: FoProductsList,
            },
            {
                path: "login",
                name: "fo-login",
                component: FoAuth,
            },
        ],
    },
    {
        path: "/admin",
        component: BoLayout,
        children: [
            {
                path: "",
                redirect: { name: "bo-home" },
            },
            {
                path: "login",
                name: "bo-login",
                component: BoLogin,
            },
            {
                path: "home",
                name: "bo-home",
                component: BoHome,
                meta: { requiresAdmin: true },
            },
            {
                path: "products",
                name: "bo-products",
                component: BoProductsList,
                meta: { requiresAdmin: true },
            },
            {
                path: "products/create",
                name: "bo-products-create",
                component: BoProductsCreate,
                meta: { requiresAdmin: true },
            },
            {
                path: "products/:id",
                name: "bo-products-edit",
                component: BoProductsEdit,
                props: true,
                meta: { requiresAdmin: true },
            },
            {
                path: "customers",
                name: "bo-customers",
                component: BoCustomersList,
                meta: { requiresAdmin: true },
            },
            {
                path: "customers/create",
                name: "bo-customers-create",
                component: BoCustomersCreate,
                meta: { requiresAdmin: true },
            },
            {
                path: "customers/:id",
                name: "bo-customers-edit",
                component: BoCustomersEdit,
                props: true,
                meta: { requiresAdmin: true },
            },
            {
                path: "orders",
                name: "bo-orders",
                component: BoOrdersList,
                meta: { requiresAdmin: true },
            },
            {
                path: "api",
                name: "bo-api",
                component: BoApiResponseViewer,
                meta: { requiresAdmin: true },
            },
            {
                path: "csv-import",
                name: "bo-csv-import",
                component: BoCsvImport,
                meta: { requiresAdmin: true },
            },
            {
                path: "data-reset",
                name: "bo-data-reset",
                component: BoDataReset,
                meta: { requiresAdmin: true },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    if (to.meta.requiresAdmin && !isAdminLoggedIn.value) {
        return {
            name: "bo-login",
            query: { redirect: to.fullPath },
        };
    }

    if (to.name === "bo-login" && isAdminLoggedIn.value) {
        return { name: "bo-home" };
    }

    return true;
});

export default router;
