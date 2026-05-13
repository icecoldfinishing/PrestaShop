<script setup lang="ts">
import { ref } from 'vue'
import { runOrderImport } from '../../../services/OrderImport.service'


/* =========================
   ETATS
========================= */
const loading = ref(false)
const logs = ref<string[]>([])
const rawOrders = ref("")

/* =========================
   CSV COMMANDES
========================= */
const orders = [
    {
        date: '09/05/2026',
        nom: 'Rakoto',
        email: 'rakoto@yopmail.com',
        pwd: 'XvzsX5O0!GBD0uXQ',
        adresse: 'Andoharanofotsy',
        achat: '[("T_01";3;"ngoza")]',
        etat: 'en attente paiement à la livraison'
    }
]

/* =========================
   LOGS
========================= */
const addLog = (msg: string) => {
    logs.value.unshift(msg)
}

/* =========================
   IMPORT COMMANDES
========================= */
async function startOrderImport() {

    loading.value = true

    logs.value = [
        '🚀 Début import commandes...'
    ]

    try {

        const source = rawOrders.value.trim() ? rawOrders.value : orders
        const count = Array.isArray(source) ? source.length : 0

        addLog(`📦 ${count || "?"} commandes détectées`)

        await runOrderImport(source, addLog)

        addLog('🎉 Import commandes terminé')

    } catch (e: any) {

        console.error(e)

        addLog(`❌ ERREUR : ${e.message}`)

    } finally {

        loading.value = false

    }
}
</script>

<template>
    <div class="p-6 max-w-4xl mx-auto bg-slate-50 min-h-screen">

        <div class="bg-white p-8 rounded-2xl shadow-xl border">

            <!-- HEADER -->
            <div class="mb-8 text-center">

                <h1 class="text-3xl font-black text-slate-800 mb-2">
                    Importation des Commandes
                </h1>

                <p class="text-slate-500">
                    Import clients + adresses + paniers + commandes + états
                </p>

            </div>

            <!-- STATS -->
            <div class="grid grid-cols-3 gap-4 mb-8">

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center">
                    <div class="text-3xl mb-2">🧾</div>
                    <div class="text-2xl font-black text-blue-700">
                        {{ orders.length }}
                    </div>
                    <div class="text-sm text-blue-500">
                        Commandes CSV
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                    <div class="text-3xl mb-2">💳</div>
                    <div class="text-sm font-bold text-green-700">
                        Paiement accepté
                    </div>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 text-center">
                    <div class="text-3xl mb-2">🛒</div>
                    <div class="text-sm font-bold text-orange-700">
                        Paiement livraison
                    </div>
                </div>

            </div>

            <!-- RAW DATA -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Donnees brutes (JSON)
                </label>

                <textarea
                    v-model="rawOrders"
                    rows="6"
                    class="w-full rounded-xl border border-slate-200 bg-white p-3 text-xs font-mono text-slate-700 shadow-inner"
                    placeholder='[{"date":"09/05/2026","nom":"Rakoto","email":"rakoto@yopmail.com","pwd":"XvzsX5O0!GBD0uXQ","adresse":"Andoharanofotsy","achat":"[(\"T_01\";3;\"ngoza\")]","etat":"en attente paiement a la livraison"}]'
                ></textarea>

                <p class="text-xs text-slate-500 mt-2">
                    Si rempli, ces donnees remplacent le tableau d'exemple.
                </p>
            </div>

            <!-- TABLE -->
            <div class="overflow-auto rounded-xl border mb-8">

                <table class="table-auto w-full text-sm">

                    <thead class="bg-slate-100 text-slate-700">

                        <tr>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Client</th>
                            <th class="p-3 text-left">Adresse</th>
                            <th class="p-3 text-left">Achats</th>
                            <th class="p-3 text-left">Etat</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr
                            v-for="(o, i) in orders"
                            :key="i"
                            class="border-t"
                        >

                            <td class="p-3">
                                {{ o.date }}
                            </td>

                            <td class="p-3">

                                <div class="font-bold">
                                    {{ o.nom }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ o.email }}
                                </div>

                            </td>

                            <td class="p-3">
                                {{ o.adresse }}
                            </td>

                            <td class="p-3 font-mono text-xs">
                                {{ o.achat }}
                            </td>

                            <td class="p-3">

                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="[
                                        o.etat.includes('accepté')
                                            ? 'bg-green-100 text-green-700'
                                            : o.etat.includes('erreur')
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-orange-100 text-orange-700'
                                    ]"
                                >
                                    {{ o.etat }}
                                </span>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- BUTTON -->
            <button
                @click="startOrderImport"
                :disabled="loading"
                :class="[
                    'w-full py-5 rounded-xl text-white font-black text-lg shadow-lg transition-all active:scale-95 mb-8',
                    loading
                        ? 'bg-slate-400 cursor-not-allowed'
                        : 'bg-indigo-600 hover:bg-indigo-700'
                ]"
            >

                {{
                    loading
                        ? 'IMPORT EN COURS...'
                        : 'LANCER IMPORT COMMANDES'
                }}

            </button>

            <!-- LOGS -->
            <div class="bg-slate-900 rounded-xl p-5 h-80 overflow-y-auto font-mono text-[11px] text-emerald-400 shadow-inner border-t-4 border-slate-700">

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