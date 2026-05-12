<script setup lang="ts">
import { ref } from 'vue'
import { runImport as runProductImport, type ImportProduct } from '../../../services/ProductImport.service'

const loading = ref(false)
const logs = ref<string[]>([])

const products: ImportProduct[] = [
    { nom: 'Tshirt', reference: 'T_01', prix_ttc: 12.5, taxe: 11.65 },
    { nom: 'Pantalon', reference: 'P_01', prix_ttc: 18.99, taxe: 11.65 },
    { nom: 'Casquette', reference: 'C_03', prix_ttc: 5, taxe: 5.6 },
    { nom: 'Montre', reference: 'M_02', prix_ttc: 56, taxe: 5.6 }
]

/* --- IMPORTATION --- */
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
    <button @click="runImport" :disabled="loading" class="bg-blue-600 text-white px-6 py-2 rounded shadow">
        {{ loading ? 'Import en cours...' : 'Lancer l\'importation sécurisée' }}
    </button>
    <div class="mt-4 bg-gray-900 text-green-400 p-4 font-mono text-xs h-80 overflow-y-auto rounded">
        <div v-for="(log, i) in logs" :key="i">> {{ log }}</div>
    </div>
</div>
</template>