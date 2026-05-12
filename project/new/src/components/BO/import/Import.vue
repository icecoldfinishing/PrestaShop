<script setup lang="ts">
import { ref } from 'vue'
import { runImport as runProductImport, type ImportProduct } from '../../../services/ProductImport.service'

const loading = ref(false)
const logs = ref<string[]>([])

const products: ImportProduct[] = [
    { nom: 'Tshirt', reference: 'T_01', prix_ttc: 12.5, taxe: 19.1, prix_achat: 8.5 },
    { nom: 'Pantalon', reference: 'P_01', prix_ttc: 18.99, taxe: 19.2, prix_achat: 14.33 },
    { nom: 'Casquette', reference: 'C_03', prix_ttc: 5, taxe: 19.3, prix_achat: 2 },
    { nom: 'Montre', reference: 'M_02', prix_ttc: 56, taxe: 19.4, prix_achat: 40 }
]

async function runImport() {
    loading.value = true
    logs.value = []

    try {
        await runProductImport(products, (message) => logs.value.push(message))
    } finally {
        loading.value = false
    }
}
</script>

<template>
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Final Import : Fix Tax Overriding</h2>
    <button @click="runImport" :disabled="loading" class="bg-blue-600 text-white px-6 py-2 rounded">
        {{ loading ? 'Importation...' : 'Lancer' }}
    </button>
    <div class="mt-4 space-y-1">
        <div v-for="(log, i) in logs" :key="i" class="p-2 bg-gray-50 border-l-4 border-blue-500 font-mono text-xs">
            {{ log }}
        </div>
    </div>
</div>
</template>