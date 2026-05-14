<script setup lang="ts">
import { ref } from 'vue'

import { runOrderImport } from '../../../services/OrderImport.service'

/* =========================
    TYPES
========================= */
type OrderRow = {
    date: string
    nom: string
    email: string
    pwd: string
    adresse: string
    achat: string
    etat: string
}

/* =========================
    ETATS
========================= */
const loading = ref(false)
const logs = ref<string[]>([])
const csvFile = ref<File | null>(null)

/* =========================
    LOGS
========================= */
const addLog = (msg: string) => {
    logs.value.unshift(msg)
}

/* =========================
    FILE
========================= */
const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement

    if (target.files && target.files[0]) {
        csvFile.value = target.files[0]
        addLog(`📄 CSV sélectionné : ${target.files[0].name}`)
    }
}

/* =========================
    PARSE CSV ORDERS
========================= */
function parseCSVOrders(file: File): Promise<any[]> {

    return new Promise((resolve, reject) => {

        const reader = new FileReader()

        reader.onload = (e) => {

            try {

                const text = String(e.target?.result || '')

                const lines = text
                    .split(/\r?\n/)
                    .filter(l => l.trim())

                if (lines.length <= 1) {
                    resolve([])
                    return
                }

                const orders: any[] = []

                for (let i = 1; i < lines.length; i++) {

                    const line = lines[i]

                    // PARSER CSV ROBUSTE
                    const values: string[] = []

                    let current = ''
                    let inQuotes = false

                    for (let j = 0; j < line.length; j++) {

                        const char = line[j]
                        const next = line[j + 1]

                        // DOUBLE QUOTE CSV ""
                        if (char === '"' && next === '"') {
                            current += '"'
                            j++
                            continue
                        }

                        // TOGGLE QUOTES
                        if (char === '"') {
                            inQuotes = !inQuotes
                            continue
                        }

                        // SEPARATEUR CSV
                        if (char === ',' && !inQuotes) {
                            values.push(current.trim())
                            current = ''
                            continue
                        }

                        current += char
                    }

                    values.push(current.trim())

                    // ETAT VIDE
                    while (values.length < 7) {
                        values.push('')
                    }

                    const [
                        date,
                        nom,
                        email,
                        pwd,
                        adresse,
                        achat,
                        etat
                    ] = values

                    console.log('PARSED =>', {
                        date,
                        nom,
                        email,
                        pwd,
                        adresse,
                        achat,
                        etat
                    })

                    orders.push({
                        date: date.trim(),
                        nom: nom.trim(),
                        email: email.trim(),
                        pwd: pwd.trim(),
                        adresse: adresse.trim(),
                        achat: achat.trim(),
                        etat: etat.trim(),
                    })
                }

                resolve(orders)

            } catch (err) {

                reject(err)
            }
        }

        reader.onerror = reject

        reader.readAsText(file)
    })
}
/* =========================
    IMPORT
========================= */
async function startImport() {

    if (!csvFile.value) {
        addLog('❌ Aucun fichier CSV sélectionné')
        return
    }

    loading.value = true

    logs.value = ['🚀 Début importation commandes...']

    try {

        addLog('📄 Analyse du CSV...')

        const parsedOrders = await parseCSVOrders(csvFile.value)

        addLog(`🛒 ${parsedOrders.length} commande(s) détectée(s)`)

        console.log(parsedOrders)

        await runOrderImport(parsedOrders, addLog)

        addLog('🎉 Commandes importées avec succès')

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
                <h1 class="text-3xl font-black text-slate-800 mb-2">
                    TEST IMPORT COMMANDES CSV
                </h1>

                <p class="text-slate-500">
                    Import commandes PrestaShop depuis CSV
                </p>
            </div>

            <!-- CSV -->
            <div
                class="mb-8 p-6 border-2 border-dashed border-indigo-200 rounded-2xl bg-indigo-50/30"
            >
                <label class="block text-sm font-bold text-indigo-700 mb-3">
                    Sélectionnez le fichier CSV commandes
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
                    file:bg-indigo-50
                    file:text-indigo-700
                    hover:file:bg-indigo-100"
                />
            </div>

            <!-- FORMAT -->
            <div class="mb-8 rounded-2xl bg-slate-900 p-5 text-emerald-400 text-xs font-mono overflow-auto">
<pre>date,nom,email,pwd,adresse,achat,etat
09/05/2026,Rakoto,rakoto@yopmail.com,XvzsX5O0!GBD0uXQ,Andoharanofotsy,"[(""T_01"";3;""ngoza"")]",

16/04/2026,Rajao,rajao1970@yopmail.com,BAC?UoxjQIW;Na8ix,Analakely,"[(""T_01"";2;""kely""),(""C_03"";1;"""")]",paiement accepté

07/05/2026,Rakoto,rakoto@yopmail.com,XvzsX5O0!GBD0uXQ,Andoharanofotsy,"[(""T_01"";1;""kely"")]",paiement accepté</pre>
            </div>

            <!-- BUTTON -->
            <button
                @click="startImport"
                :disabled="loading || !csvFile"
                :class="[
                    'w-full py-5 rounded-2xl text-white font-black text-lg shadow-lg transition-all active:scale-95 mb-8',
                    loading || !csvFile
                        ? 'bg-slate-400 cursor-not-allowed'
                        : 'bg-indigo-600 hover:bg-indigo-700'
                ]"
            >
                {{
                    loading
                        ? 'TRAITEMENT EN COURS...'
                        : 'LANCER IMPORT COMMANDES'
                }}
            </button>

            <!-- LOGS -->
            <div
                class="bg-slate-900 rounded-2xl p-5 h-96 overflow-y-auto font-mono text-[11px] text-emerald-400 shadow-inner border-t-4 border-slate-700"
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