<script setup lang="ts">
import { ref } from 'vue'
import { runImport as runProductImport } from '../../../services/ProductImport.service'
import { runCombinationImport } from '../../../services/CombinationImport.service'

/* --- ÉTATS --- */
const loading = ref(false)
const logs = ref<string[]>([])
const importType = ref<'products' | 'combinations'>('products')

/* --- DONNÉES FIXES --- */
const products = [
    { nom: 'Tshirt', reference: 'T_01', prix_ttc: 12.5, taxe: 11.65, prix_achat: 8.5 },
    { nom: 'Pantalon', reference: 'P_01', prix_ttc: 18.99, taxe: 11.65, prix_achat: 14.33 },
    { nom: 'Casquette', reference: 'C_03', prix_ttc: 5, taxe: 5.6, prix_achat: 2 },
    { nom: 'Montre', reference: 'M_02', prix_ttc: 56, taxe: 5.6, prix_achat: 40 }
]

const combinations = [
    { reference: 'T_01', specificite: 'taille', karazany: 'ngoza', stock: 13, prix: 12.5 },
    { reference: 'T_01', specificite: 'taille', karazany: 'kely', stock: 10, prix: 15 },
    { reference: 'P_01', specificite: 'couleur', karazany: 'mainty', stock: 5, prix: 23.49 },
    { reference: 'P_01', specificite: 'couleur', karazany: 'fotsy', stock: 3, prix: 18.99 }
]

const addLog = (m: string) => logs.value.unshift(m)

/* --- FONCTION DE LANCEMENT --- */
async function startGlobalImport() {
    loading.value = true
    logs.value = [`🚀 Début de l'importation complète...`]

    try {
        if (importType.value === 'products') {
            addLog(`📦 Mode : Tous les Produits (${products.length})`)
            await runProductImport(products, addLog)
        } else {
            addLog(`🧩 Mode : Toutes les Déclinaisons (${combinations.length})`)
            await runCombinationImport(combinations, addLog)
        }
        addLog("🎉 Importation terminée !")
    } catch (e: any) {
        addLog(`❌ ERREUR : ${e.message}`)
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="p-6 max-w-3xl mx-auto bg-slate-50 min-h-screen">
        <div class="bg-white p-8 rounded-2xl shadow-xl border">
            <h1 class="text-2xl font-bold mb-6 text-slate-800 text-center">Panel d'Importation</h1>

            <!-- Choix du type d'import -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <label :class="['flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all', importType === 'products' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-400']">
                    <input type="radio" value="products" v-model="importType" class="hidden">
                    <span class="text-3xl mb-2">📦</span>
                    <span class="font-bold">TOUS LES PRODUITS</span>
                </label>

                <label :class="['flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all', importType === 'combinations' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-200 text-gray-400']">
                    <input type="radio" value="combinations" v-model="importType" class="hidden">
                    <span class="text-3xl mb-2">🧩</span>
                    <span class="font-bold">TOUTES LES DÉCLINAISONS</span>
                </label>
            </div>

            <!-- Bouton Unique -->
            <button 
                @click="startGlobalImport" 
                :disabled="loading"
                :class="[
                    'w-full py-5 rounded-xl text-white font-black text-lg shadow-lg transition-all active:scale-95 mb-8',
                    importType === 'products' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-purple-600 hover:bg-purple-700',
                    loading ? 'opacity-50 cursor-not-allowed' : ''
                ]"
            >
                {{ loading ? 'TRAITEMENT EN COURS...' : 'LANCER L\'IMPORTATION TOTALE' }}
            </button>

            <!-- Console de Logs -->
            <div class="bg-slate-900 rounded-xl p-5 h-72 overflow-y-auto font-mono text-[11px] text-emerald-400 shadow-inner border-t-4 border-slate-700">
                <div v-for="(l, i) in logs" :key="i" class="mb-1 border-b border-slate-800 pb-1">
                    <span class="opacity-50">[{{ new Date().toLocaleTimeString() }}]</span> {{ l }}
                </div>
            </div>
        </div>
    </div>
</template>