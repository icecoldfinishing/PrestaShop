<script setup>
import { ref, onMounted } from 'vue';
import { getXmlText, psGet } from '../../utils/prestashop-api';

const orders = ref([]);

const getAllOrders = async () => {
    try {
        const data = await psGet('orders', '', {
            display: '[id,id_customer,total_paid,current_state,date_add]',
        });

        const orderData = data?.prestashop?.orders?.order;

        if (!orderData) {
            orders.value = [];
            return;
        }

        const ordersArray = Array.isArray(orderData)
            ? orderData
            : [orderData];

        orders.value = ordersArray.map(o => {
            return {
                id: o.id,
                id_customer: getXmlText(o.id_customer),
                total_paid: parseFloat(o.total_paid || 0).toFixed(2),                
                current_state: getXmlText(o.current_state),
                date_add: getXmlText(o.date_add)
            };
        });

    } catch (error) {
        console.error('Error fetching orders:', error);
    }
};

onMounted(() => {
    getAllOrders();
});
</script>

<template>
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Order List</h2>

        <div v-if="orders.length" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col" v-for="order in orders" :key="order.id">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-2">
                            Order #{{ order.id }}
                        </h5>

                        <p class="mb-1 text-muted">
                            Customer ID: {{ order.id_customer }}
                        </p>

                        <p class="mb-1">
                            Status: {{ order.current_state }}
                        </p>

                        <p class="mb-1 text-primary fw-bold">
                            ${{ order.total_paid }}
                        </p>

                        <p class="text-muted small">
                            {{ order.date_add }}
                        </p>

                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-muted py-5 text-center">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            Loading orders...
        </div>
    </div>
</template>