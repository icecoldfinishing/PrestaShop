<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { XMLParser } from 'fast-xml-parser'

/* ---------------- CONFIG ---------------- */
const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

const parser = new XMLParser({
    ignoreAttributes: false,
    attributeNamePrefix: '@_',
    parseTagValue: true,
    parseAttributeValue: true,
})

const sleep = (ms: number) => new Promise(res => setTimeout(res, ms));

/* ---------------- API WRAPPERS ---------------- */
async function localPsGet(resource: string, id = '', params = {}) {
    const url = `${BASE_URL}/${id ? `${resource}/${id}` : resource}`
    const res = await axios.get(url, {
        params: {
            ws_key: API_KEY,
            output_format: 'XML',
            ...params
        },
        headers: { Accept: 'application/xml' }
    })
    return parser.parse(res.data)
}

async function localPsPost(resource: string, xml: string) {
    const res = await axios.post(`${BASE_URL}/${resource}`, xml, {
        params: { ws_key: API_KEY },
        headers: {
            'Content-Type': 'application/xml',
            'Accept': 'application/xml'
        }
    })
    return res.data
}

async function localPsPut(resource: string, xml: string) {
    const res = await axios.put(`${BASE_URL}/${resource}`, xml, {
        params: { ws_key: API_KEY },
        headers: {
            'Content-Type': 'application/xml',
            'Accept': 'application/xml'
        }
    })
    return res.data
}

function getXmlText(value: any) {
    if (!value) return ''
    if (typeof value === 'object') return value['#text'] ?? String(value)
    return String(value)
}

/* ---------------- STATE ---------------- */
const loading = ref(false)
const logs = ref<string[]>([])
const reference = ref('T_01')

const rows = ref([
    { spec: 'taille', value: 'ngoza', stock: 13, price: 12.5 },
    { spec: 'taille', value: 'kely', stock: 10, price: 15 }
])

const log = (m: string) => {
    logs.value.unshift(m)
    console.log(m)
}

/* ---------------- LOGIQUE METIER ---------------- */

// 1. Récupérer les infos du parent (ID et Prix HT de base)
async function getParentData(ref: string) {
    const res = await localPsGet('products', '', {
        display: '[id,price]',
        'filter[reference]': `[${ref}]`
    })

    const p = res?.prestashop?.products?.product
    const product = Array.isArray(p) ? p[0] : p

    if (!product) throw new Error(`Produit parent "${ref}" introuvable`)
    
    return {
        id: getXmlText(product.id),
        basePriceHT: parseFloat(getXmlText(product.price) || '0')
    }
}

// 2. Créer la déclinaison avec calcul d'impact HT précis
async function createCombination(productId: string, basePriceHT: number, row: any) {
    // CALCUL PRÉCIS : (Prix TTC Final / 1.191) - Prix Base HT Parent
    const targetPriceHT = row.price / 1.191;
    const impactHT = targetPriceHT - basePriceHT;

    const xml = `
<prestashop>
  <combination>
    <id_product>${productId}</id_product>
    <reference><![CDATA[${reference.value}_${row.value}]]></reference>
    <price>${impactHT.toFixed(6)}</price>
    <minimal_quantity>1</minimal_quantity>
    <default_on>0</default_on>
    <associations>
      <product_option_values>
        <product_option_value>
          <id>1</id> 
        </product_option_value>
      </product_option_values>
    </associations>
  </combination>
</prestashop>`

    const res = await localPsPost('combinations', xml)
    const doc = new DOMParser().parseFromString(res, 'text/xml')
    const id = doc.getElementsByTagName('id')[0]?.textContent

    if (!id) throw new Error("Échec de création de la déclinaison")
    return id
}

// 3. Mettre à jour le stock (avec retry logic pour la stabilité)
async function setStock(productId: string, combinationId: string, qty: number) {
    try {
        // Attente indispensable pour que PS crée l'entrée en BDD
        await sleep(800);

        const res = await localPsGet('stock_availables', '', {
            'filter[id_product]': `[${productId}]`,
            'filter[id_product_attribute]': `[${combinationId}]`,
            display: 'full'
        })

        const stockData = res?.prestashop?.stock_availables?.stock_available
        const stock = Array.isArray(stockData) ? stockData[0] : stockData

        if (!stock?.id) {
            throw new Error("Lien stock introuvable. Réessayez ou augmentez le sleep.")
        }

        const xml = `
<prestashop>
  <stock_available>
    <id>${stock.id}</id>
    <id_product>${productId}</id_product>
    <id_product_attribute>${combinationId}</id_product_attribute>
    <quantity>${qty}</quantity>
    <id_shop>1</id_shop>
    <out_of_stock>2</out_of_stock>
    <depends_on_stock>0</depends_on_stock>
  </stock_available>
</prestashop>`

        await localPsPut('stock_availables', xml)
        log(`✅ STOCK MIS À JOUR : ${qty} (ID Stock: ${stock.id})`)

    } catch (e: any) {
        log(`❌ Erreur stock: ${e.message}`)
    }
}

/* ---------------- MAIN ---------------- */
async function generate() {
    loading.value = true
    logs.value = []

    try {
        log(`🚀 Démarrage : ${reference.value}`)

        // Récupération des données parent
        const parent = await getParentData(reference.value)
        log(`📦 Parent ID: ${parent.id} | Prix Base HT: ${parent.basePriceHT.toFixed(4)}`)

        for (const row of rows.value) {
            log(`⚙️ Traitement de : ${row.value} (TTC visé: ${row.price}€)`)

            // 1. Créer la déclinaison
            const combinationId = await createCombination(parent.id, parent.basePriceHT, row)
            log(`✔ Déclinaison ID=${combinationId} créée`)

            // 2. Mettre à jour le stock
            await setStock(parent.id, combinationId, row.stock)
        }

        log(`🎉 OPÉRATION TERMINÉE AVEC SUCCÈS`)

    } catch (e: any) {
        log(`❌ ERREUR MAJEURE: ${e.message}`)
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="p-6 max-w-4xl mx-auto font-sans text-gray-800">
        <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
            <h2 class="text-2xl font-black mb-6 text-indigo-900">🚀 PrestaShop Variant Generator</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Référence Parent</label>
                    <input v-model="reference"
                        class="w-full border-2 border-gray-200 p-3 rounded-lg focus:border-indigo-500 outline-none transition-all font-mono" />
                </div>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-lg mb-6">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-xs font-bold text-gray-500 uppercase">Valeur</th>
                            <th class="p-3 text-xs font-bold text-gray-500 uppercase">Stock</th>
                            <th class="p-3 text-xs font-bold text-gray-500 uppercase">Prix Final TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, i) in rows" :key="i"
                            class="border-t border-gray-100 hover:bg-indigo-50/30 transition-colors">
                            <td class="p-3">
                                <input v-model="r.value" class="border p-2 w-full rounded text-sm font-medium"
                                    placeholder="ex: XL, Rouge..." />
                            </td>
                            <td class="p-3">
                                <input v-model.number="r.stock" type="number" class="border p-2 w-24 rounded text-sm" />
                            </td>
                            <td class="p-3">
                                <div class="relative">
                                    <input v-model.number="r.price" type="number" step="0.01"
                                        class="border p-2 w-full rounded text-sm font-bold text-indigo-600 pl-7" />
                                    <span class="absolute left-2 top-2 text-gray-400">€</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button @click="generate" :disabled="loading"
                class="w-full py-4 rounded-xl font-black uppercase tracking-widest text-white shadow-lg transition-all active:scale-95"
                :class="loading ? 'bg-gray-400' : 'bg-indigo-600 hover:bg-indigo-700 hover:shadow-indigo-200'">
                {{ loading ? 'Synchronisation...' : 'Générer les variantes' }}
            </button>
        </div>

        <div class="mt-8 bg-gray-900 rounded-xl p-5 shadow-2xl border border-gray-800">
            <div class="h-64 overflow-y-auto font-mono text-xs leading-relaxed text-indigo-200">
                <div v-for="(l, i) in logs" :key="i"
                    :class="l.includes('❌') ? 'text-red-400' : l.includes('✅') || l.includes('🎉') ? 'text-emerald-400' : ''"
                    class="mb-1 border-l-2 border-gray-700 pl-3 py-0.5">
                    <span class="text-gray-600 mr-2">{{ new Date().toLocaleTimeString() }}</span> {{ l }}
                </div>
                <div v-if="logs.length === 0" class="text-gray-500 italic">Prêt pour l'importation...</div>
            </div>
        </div>
    </div>
</template>