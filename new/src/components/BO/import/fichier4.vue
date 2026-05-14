<script setup lang="ts">
import { ref } from 'vue'

import { runImport as runProductImport } from '../../../services/ProductImport.service'
import { runCombinationImport } from '../../../services/CombinationImport.service'
import { runOrderImport } from '../../../services/OrderImport.service'
import { ImageImportService } from '../../../services/ImageImport.service'

/* =========================
    ETATS
========================= */
const loading = ref(false)
const logs = ref<string[]>([])

// Ajout de 'images' dans le type
const importType = ref<'products' | 'combinations' | 'orders' | 'images'>('products')

const rawOrders = ref("")
const zipFile = ref<File | null>(null) // Stockage pour le fichier ZIP sélectionné


const addLog = (msg: string) => {
    logs.value.unshift(msg)
}

// Handler pour la sélection du ZIP
const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement
    if (target.files && target.files[0]) {
        zipFile.value = target.files[0]
        addLog(`📂 ZIP sélectionné : ${target.files[0].name}`)
    }
}

/* =========================
    IMPORT GLOBAL
========================= */
async function startGlobalImport() {
    loading.value = true
    logs.value = ['🚀 Début importation...']

    try {
        /* ================= PRODUCTS ================= */
        /* ================= IMAGES (ZIP) ================= */
        if (importType.value === 'images') {
            if (!zipFile.value) {
                addLog("❌ Erreur : Aucun fichier ZIP sélectionné.")
            } else {
                addLog(`🖼️ Extraction et envoi du ZIP : ${zipFile.value.name}`)
                await ImageImportService.processZip(zipFile.value, (msg) => addLog(msg))
                addLog('🎉 Importation des images terminée')
            }
        }

    } catch (e: any) {
        console.error(e)
        addLog(`❌ ERREUR : ${e.message}`)
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="p-6 max-w-5xl mx-auto bg-slate-50 min-h-screen">
        <div class="bg-white p-8 rounded-2xl shadow-xl border">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-slate-800 mb-2">Panel d'Importation</h1>
                <p class="text-slate-500">Produits + Déclinaisons + Commandes + Images</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                
                <label :class="['flex flex-col items-center p-5 border-2 rounded-2xl cursor-pointer transition-all', importType === 'images' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-400']">
                    <input type="radio" value="images" v-model="importType" class="hidden">
                    <div class="text-4xl mb-3">🖼️</div>
                    <div class="font-black">IMAGES ZIP</div>
                    <div class="text-xs mt-2">Format .zip</div>
                </label>
            </div>

            <div v-if="importType === 'images'" class="mb-8 p-6 border-2 border-dashed border-slate-200 rounded-2xl text-center bg-slate-50">
                <input type="file" accept=".zip" @change="onFileChange" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
            </div>

            <div v-if="importType === 'orders'" class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Données commandes (JSON)</label>
                <textarea v-model="rawOrders" rows="5" class="w-full rounded-2xl border border-slate-200 bg-white p-4 text-xs font-mono shadow-inner" placeholder='[{"date":"09/05/2026", ...}]'></textarea>
            </div>

            <button @click="startGlobalImport" :disabled="loading || (importType === 'images' && !zipFile)"
                :class="['w-full py-5 rounded-2xl text-white font-black text-lg shadow-lg transition-all active:scale-95 mb-8', 
                importType === 'products' ? 'bg-blue-600 hover:bg-blue-700' : importType === 'combinations' ? 'bg-purple-600 hover:bg-purple-700' : importType === 'images' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-indigo-600 hover:bg-indigo-700', 
                loading ? 'opacity-50 cursor-not-allowed' : '']">
                {{ loading ? 'TRAITEMENT EN COURS...' : 'LANCER IMPORTATION' }}
            </button>

            <div class="bg-slate-900 rounded-2xl p-5 h-80 overflow-y-auto font-mono text-[11px] text-emerald-400 shadow-inner border-t-4 border-slate-700">
                <div v-for="(l, i) in logs" :key="i" class="mb-1 border-b border-slate-800 pb-1">
                    <span class="opacity-50">[{{ new Date().toLocaleTimeString() }}]</span> {{ l }}
                </div>
            </div>
        </div>
    </div>
</template>