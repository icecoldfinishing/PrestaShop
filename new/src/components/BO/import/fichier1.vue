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

const importType = ref<'products' | 'combinations' | 'orders' | 'images'>('products')

const rawOrders = ref("")
const zipFile = ref<File | null>(null) // Stockage pour le fichier ZIP (Images)
const csvFile = ref<File | null>(null) // Stockage pour le fichier CSV (Produits)

/* =========================
    LOGS
========================= */
const addLog = (msg: string) => {
    logs.value.unshift(msg)
}

// Handler unique pour la sélection des fichiers
const onFileChange = (e: Event, type: 'csv' | 'zip') => {
    const target = e.target as HTMLInputElement
    if (target.files && target.files[0]) {
        if (type === 'csv') {
            csvFile.value = target.files[0]
            addLog(`📄 CSV Sélectionné : ${target.files[0].name}`)
        } else if (type === 'zip') {
            zipFile.value = target.files[0]
            addLog(`📂 ZIP sélectionné : ${target.files[0].name}`)
        }
    }
}

/* =========================
    PARSING & MAPPING CSV
========================= */
function parseCSVProducts(file: File): Promise<any[]> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()
        
        reader.onload = (e) => {
            const text = e.target?.result as string
            if (!text) return resolve([])

            // Découpage par ligne (gère CRLF et LF)
            const lines = text.split(/\r?\n/)
            if (lines.length < 2) return resolve([])

            // Extraction et nettoyage des en-têtes (première ligne)
            const headers = lines[0].split(',').map(h => h.trim().toLowerCase())

            const parsedProducts: any[] = []

            for (let i = 1; i < lines.length; i++) {
                const line = lines[i].trim()
                if (!line) continue // Évite les lignes vides en fin de fichier

                // Regex pour séparer par virgule tout en ignorant les virgules à l'intérieur des guillemets
                const matches = line.match(/(".*?"|[^",\s]+)(?=\s*,|\s*$)/g) || line.split(',')
                const values = matches.map(v => v.replace(/^"|"$/g, '').trim())

                if (values.length < headers.length) continue

                // Création d'un objet temporaire basé sur le nom des colonnes du CSV
                const rawProduct: any = {}
                headers.forEach((header, index) => {
                    rawProduct[header] = values[index]
                })

                // Nettoyage et mapping vers le format attendu par ton service PrestaShop
                parsedProducts.push({
                    date_availability_produit: rawProduct['date_availability_produit'] || '',
                    nom: rawProduct['nom'] || '',
                    reference: rawProduct['reference'] || '',
                    // Remplace la virgule par un point pour JavaScript (ex: "12,5" -> 12.5)
                    prix_ttc: parseFloat(rawProduct['prix_ttc']?.replace(',', '.')) || 0,
                    // Retire le symbole % et convertit en float (ex: "11,65%" -> 11.65)
                    taxe: parseFloat(rawProduct['taxe']?.replace('%', '').replace(',', '.')) || 0,
                    categorie: rawProduct['categorie'] || '',
                    prix_achat: parseFloat(rawProduct['prix_achat']?.replace(',', '.')) || 0
                })
            }

            resolve(parsedProducts)
        }

        reader.onerror = (err) => reject(err)
        reader.readAsText(file)
    })
}

/* =========================
    IMPORT GLOBAL
========================= */
async function startGlobalImport() {
    loading.value = true
    logs.value = ['🚀 Début importation...']

    try {
        /* ================= PRODUCTS ================= */
        if (importType.value === 'products') {
            if (!csvFile.value) {
                addLog('❌ Erreur : Aucun fichier CSV sélectionné.')
                loading.value = false
                return
            }

            addLog('⏳ Analyse et conversion du fichier CSV...')
            const dynamicProducts = await parseCSVProducts(csvFile.value)

            if (dynamicProducts.length === 0) {
                addLog('⚠️ Aucun produit valide trouvé dans le CSV.')
                loading.value = false
                return
            }

            addLog(`📦 Import Produits (${dynamicProducts.length} éléments détectés)`)
            await runProductImport(dynamicProducts, addLog)
            addLog('🎉 Produits importés avec succès !')
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
                <label :class="['flex flex-col items-center p-5 border-2 rounded-2xl cursor-pointer transition-all', importType === 'products' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-400']">
                    <input type="radio" value="products" v-model="importType" class="hidden">
                    <div class="text-4xl mb-3">📦</div>
                    <div class="font-black">PRODUITS</div>
                    <div class="text-xs mt-2">{{ csvFile ? 'Fichier prêt' : 'CSV requis' }}</div>
                </label>
            </div>

            <div v-if="importType === 'products'" class="mb-8 p-6 border-2 border-dashed border-blue-200 rounded-2xl text-center bg-blue-50/30">
                <label class="block text-sm font-bold text-blue-700 mb-2">Déposez ou sélectionnez votre fichier CSV produits</label>
                <input type="file" accept=".csv" @change="onFileChange($event, 'csv')" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            </div>

            <div v-if="importType === 'images'" class="mb-8 p-6 border-2 border-dashed border-slate-200 rounded-2xl text-center bg-slate-50">
                <label class="block text-sm font-bold text-emerald-700 mb-2">Sélectionnez le fichier ZIP des images</label>
                <input type="file" accept=".zip" @change="onFileChange($event, 'zip')" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
            </div>

            <div v-if="importType === 'orders'" class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Données commandes (JSON)</label>
                <textarea v-model="rawOrders" rows="5" class="w-full rounded-2xl border border-slate-200 bg-white p-4 text-xs font-mono shadow-inner" placeholder='[{"date":"09/05/2026", ...}]'></textarea>
            </div>

            <button @click="startGlobalImport" 
                :disabled="loading || (importType === 'images' && !zipFile) || (importType === 'products' && !csvFile)"
                :class="['w-full py-5 rounded-2xl text-white font-black text-lg shadow-lg transition-all active:scale-95 mb-8', 
                importType === 'products' ? 'bg-blue-600 hover:bg-blue-700' : importType === 'combinations' ? 'bg-purple-600 hover:bg-purple-700' : importType === 'images' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-indigo-600 hover:bg-indigo-700', 
                (loading || (importType === 'images' && !zipFile) || (importType === 'products' && !csvFile)) ? 'opacity-50 cursor-not-allowed' : '']">
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