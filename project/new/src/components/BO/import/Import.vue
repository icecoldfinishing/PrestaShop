<script setup lang="ts">
import { ref } from 'vue'
import { sleep } from '../../../services/prestashopApi.service';
import { ensureCustomer, ensureAddress, createCart, createOrder } from '../../../services/OrderImport.service';

interface OrderData {
    date: string;
    nom: string;
    email: string;
    pwd?: string;
    adresse: string;
    achat: string; 
    etat: string;
}

const loading = ref(false)
const logs = ref<string[]>([])
const orders = ref<OrderData[]>([
    {
        date: "07/05/2026", 
        nom: "Rakoto", 
        email: "rakoto@yopmail.com", 
        pwd: "XvzsX5O0!GBD0uXQ", 
        adresse: "Andoharanofotsy", 
        achat: `[("T_01";1;"kely")]`, 
        etat: "erreur de paiement"
    }
])

const addLog = (m: string) => logs.value.unshift(m)

async function startFullImport() {
    loading.value = true;
    logs.value = ["🚀 Initialisation de l'import..."];

    for (const order of orders.value) {
        addLog(`--- 📦 Traitement de ${order.nom} ---`);
        
        const customer = await ensureCustomer(order, addLog);
        if (!customer) {
            continue;
        }

        const aId = await ensureAddress(customer.id, order, addLog);
        if (!aId) {
            continue;
        }

        const cartInfo = await createCart(customer.id, aId, order, addLog);
        if (cartInfo) {
            await createOrder(cartInfo, customer.id, aId, order, addLog, customer.secure_key);
        }
        await sleep(1000);
    }
    loading.value = false;
    addLog("🏁 TERMINÉ");
}
</script>

<template>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white p-8 rounded-2xl shadow-xl border">
            <h1 class="text-2xl font-bold mb-6 italic text-indigo-900 underline">Importateur PrestaShop</h1>
            
            <button @click="startFullImport" :disabled="loading"
                class="w-full py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg disabled:bg-gray-400">
                {{ loading ? 'Importation...' : 'Lancer le Processus Complet' }}
            </button>

            <div class="mt-8 bg-gray-900 rounded-xl p-5 h-96 overflow-y-auto font-mono text-[11px] text-indigo-300 border-t-4 border-indigo-500">
                <div v-for="(l, i) in logs" :key="i" class="mb-1 py-1 border-b border-gray-800">> {{ l }}</div>
            </div>
        </div>
    </div>
</template>