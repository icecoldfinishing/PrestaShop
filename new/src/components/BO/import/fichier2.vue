<script setup lang="ts">
import { ref } from 'vue'

import { runCombinationImport } from '../../../services/CombinationImport.service'

/* =========================
    ETATS
========================= */
const loading = ref(false)
const logs = ref<string[]>([])

const importType = ref<'combinations'>('combinations')

const csvFile = ref<File | null>(null)

/* =========================
    TYPES
========================= */
type CombinationImportRow = {
    reference: string
    specificite: string
    karazany: string
    stock: number
    prix: number | null
}

/* =========================
    LOGS
========================= */
const addLog = (msg: string) => {
    logs.value.unshift(msg)
}

/* =========================
    FILE HANDLER
========================= */
const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement

    if (target.files && target.files[0]) {
        csvFile.value = target.files[0]

        addLog(`📄 CSV sélectionné : ${target.files[0].name}`)
    }
}

function parseCSVCombinations(file: File): Promise<CombinationImportRow[]> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()

        reader.onload = (e) => {
            try {
                const text = e.target?.result as string
                if (!text) return resolve([])

                const lines = text
                    .split(/\r?\n/)
                    .map(l => l.trim())
                    .filter(Boolean)

                if (lines.length < 2) return resolve([])

                const headers = parseCSVLine(lines[0]).map(h =>
                    h.trim().toLowerCase()
                )

                const results: CombinationImportRow[] = []

                for (let i = 1; i < lines.length; i++) {
                    const values = parseCSVLine(lines[i])

                    const row: Record<string, string> = {}

                    headers.forEach((header, index) => {
                        row[header] = values[index] ?? ''
                    })

                    const stockRaw = row['stock_initial']?.trim()
                    const prixRaw = row['prix_vente_ttc']?.trim()

                    results.push({
                        reference: row['reference'] || '',
                        specificite:
                            row['specificité'] ||
                            row['specificite'] ||
                            '',
                        karazany: row['karazany'] || '',

                        stock:
                            stockRaw !== ''
                                ? Number.parseInt(stockRaw, 10)
                                : 0,

                        prix:
                            prixRaw && prixRaw !== ''
                                ? Number(
                                      prixRaw
                                          .replace(',', '.')
                                          .replace(/[^0-9.]/g, '')
                                  )
                                : null
                    })
                }

                resolve(results)
            } catch (err) {
                reject(err)
            }
        }

        reader.onerror = reject
        reader.readAsText(file)
    })
}

/* =========================
   CSV SAFE SPLITTER (IMPORTANT)
========================= */
function parseCSVLine(line: string): string[] {
    const result: string[] = []
    let current = ''
    let inQuotes = false

    for (let i = 0; i < line.length; i++) {
        const char = line[i]

        if (char === '"') {
            inQuotes = !inQuotes
        } else if (char === ',' && !inQuotes) {
            result.push(current)
            current = ''
        } else {
            current += char
        }
    }

    result.push(current)

    return result.map(v =>
        v.trim().replace(/^"|"$/g, '')
    )
}

/* =========================
    IMPORT GLOBAL
========================= */
async function startGlobalImport() {

    if (!csvFile.value) {
        addLog('❌ Aucun fichier CSV sélectionné')
        return
    }

    loading.value = true

    logs.value = ['🚀 Début importation...']

    try {

        /* =========================
            PARSE CSV
        ========================= */

        addLog('📄 Analyse du fichier CSV...')

        const combinations =
            await parseCSVCombinations(csvFile.value)

        if (combinations.length === 0) {
            addLog('⚠️ Aucune déclinaison valide détectée')
            loading.value = false
            return
        }

        addLog(
            `🧩 ${combinations.length} déclinaison(s) détectée(s)`
        )

        console.table(combinations)

        /* =========================
            IMPORT
        ========================= */

        await runCombinationImport(
            combinations,
            addLog
        )

        addLog('🎉 Déclinaisons importées avec succès')

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

            <!-- HEADER -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-slate-800 mb-2">
                    Import Déclinaisons CSV
                </h1>

                <p class="text-slate-500">
                    Import automatique des combinaisons PrestaShop
                </p>
            </div>

            <!-- TYPE -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-8">

                <label
                    :class="[
                        'flex flex-col items-center p-5 border-2 rounded-2xl cursor-pointer transition-all',
                        importType === 'combinations'
                            ? 'border-purple-500 bg-purple-50 text-purple-700'
                            : 'border-slate-200 text-slate-400'
                    ]"
                >
                    <input
                        type="radio"
                        value="combinations"
                        v-model="importType"
                        class="hidden"
                    >

                    <div class="text-4xl mb-3">
                        🧩
                    </div>

                    <div class="font-black">
                        DÉCLINAISONS
                    </div>

                    <div class="text-xs mt-2">
                        CSV requis
                    </div>
                </label>

            </div>

            <!-- CSV -->
            <div
                class="mb-8 p-6 border-2 border-dashed border-purple-200 rounded-2xl text-center bg-purple-50/30"
            >

                <label
                    class="block text-sm font-bold text-purple-700 mb-2"
                >
                    Sélectionnez le fichier CSV des déclinaisons
                </label>

                <input
                    type="file"
                    accept=".csv"
                    @change="onFileChange"
                    class="block w-full text-sm text-slate-500
                    file:mr-4
                    file:py-2
                    file:px-4
                    file:rounded-full
                    file:border-0
                    file:text-sm
                    file:font-semibold
                    file:bg-purple-50
                    file:text-purple-700
                    hover:file:bg-purple-100"
                />

            </div>

            <!-- BUTTON -->
            <button
                @click="startGlobalImport"
                :disabled="loading || !csvFile"
                :class="[
                    'w-full py-5 rounded-2xl text-white font-black text-lg shadow-lg transition-all active:scale-95 mb-8',
                    'bg-purple-600 hover:bg-purple-700',
                    (loading || !csvFile)
                        ? 'opacity-50 cursor-not-allowed'
                        : ''
                ]"
            >
                {{
                    loading
                        ? 'TRAITEMENT EN COURS...'
                        : 'LANCER IMPORTATION'
                }}
            </button>

            <!-- LOGS -->
            <div
                class="bg-slate-900 rounded-2xl p-5 h-80 overflow-y-auto font-mono text-[11px] text-emerald-400 shadow-inner border-t-4 border-slate-700"
            >

                <div
                    v-for="(l, i) in logs"
                    :key="i"
                    class="mb-1 border-b border-slate-800 pb-1"
                >
                    <span class="opacity-50">
                        [{{ new Date().toLocaleTimeString() }}]
                    </span>

                    {{ l }}
                </div>

            </div>

        </div>
    </div>
</template>