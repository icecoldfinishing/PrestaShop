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

// 1. Récupérer l'ID du produit via sa référence
async function getProductId(ref: string) {
    const res = await localPsGet('products', '', {
        display: 'full',
        'filter[reference]': `[${ref}]`
    })

    const p = res?.prestashop?.products?.product
    const product = Array.isArray(p) ? p[0] : p

    if (!product) throw new Error(`Produit avec la référence "${ref}" introuvable`)
    return getXmlText(product.id)
}

// 2. Créer la déclinaison (Combination)
async function createCombination(productId: string, row: any) {
    // Note: l'ID de l'option de valeur (id: 1) doit exister dans votre PS
    const xml = `
<prestashop>
  <combination>
    <id_product>${productId}</id_product>
    <reference><![CDATA[${reference.value}_${row.value}]]></reference>
    <price>${row.price}</price>
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

// 3. Mettre à jour le stock (La partie délicate)
async function setStock(productId: string, combinationId: string, qty: number) {
    try {
        // Un court délai permet à PrestaShop de générer la ligne en base de données
        await sleep(700);

        // Récupération de l'ID stock généré automatiquement
        const res = await localPsGet('stock_availables', '', {
            'filter[id_product]': `[${productId}]`,
            'filter[id_product_attribute]': `[${combinationId}]`,
            display: 'full'
        })

        const stockData = res?.prestashop?.stock_availables?.stock_available
        const stock = Array.isArray(stockData) ? stockData[0] : stockData

        if (!stock?.id) {
            throw new Error("Lien stock (stock_available) introuvable pour cette déclinaison")
        }

        // PUT vers stock_availables avec les champs obligatoires
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
        log(`✅ STOCK MIS À JOUR : ${qty}`)

    } catch (e: any) {
        console.error("Détail Erreur Stock:", e?.response?.data || e)
        log(`❌ Erreur stock: ${e.message}`)
    }
}

/* ---------------- MAIN ---------------- */
async function generate() {
    loading.value = true
    logs.value = []

    try {
        log(`🚀 Démarrage du processus pour ${reference.value}`)

        const productId = await getProductId(reference.value)
        log(`📦 Produit Parent ID : ${productId}`)

        for (const row of rows.value) {
            log(`⚙️ Traitement de : ${row.value}`)

            // Création de la déclinaison
            const combinationId = await createCombination(productId, row)
            log(`✔ Déclinaison ID=${combinationId} créée`)

            // Mise à jour du stock pour cette déclinaison
            await setStock(productId, combinationId, row.stock)
        }

        log(`🎉 OPÉRATION TERMINÉE`)

    } catch (e: any) {
        log(`❌ ERREUR MAJEURE: ${e.message}`)
        console.error(e)
    } finally {
        loading.value = false
    }
}
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto font-sans">
    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
      <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Générateur de Déclinaisons PrestaShop
      </h2>

      <!-- REF -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Référence du produit parent</label>
        <input
            v-model="reference"
            class="border border-gray-300 p-2 w-full rounded focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Ex: T_01"
        />
      </div>

      <!-- TABLE -->
      <div class="overflow-x-auto mb-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-300">
                    <th class="p-2 text-left text-xs font-semibold text-gray-600 uppercase">Valeur</th>
                    <th class="p-2 text-left text-xs font-semibold text-gray-600 uppercase">Stock</th>
                    <th class="p-2 text-left text-xs font-semibold text-gray-600 uppercase">Impact Prix HT</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(r,i) in rows" :key="i" class="border-b border-gray-100">
                    <td class="p-2 text-sm text-gray-700">
                        <input v-model="r.value" class="border p-1 w-full rounded bg-gray-50"/>
                    </td>
                    <td class="p-2 text-sm">
                        <input v-model.number="r.stock" type="number" class="border p-1 w-full rounded"/>
                    </td>
                    <td class="p-2 text-sm">
                        <input v-model.number="r.price" type="number" class="border p-1 w-full rounded"/>
                    </td>
                </tr>
            </tbody>
        </table>
      </div>

      <!-- BUTTON -->
      <button
          @click="generate"
          :disabled="loading"
          class="w-full py-3 px-4 rounded font-bold text-white transition-all shadow-md"
          :class="loading ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 active:transform active:scale-95'"
      >
          {{ loading ? 'Traitement en cours...' : 'Lancer la génération' }}
      </button>
    </div>

    <!-- LOGS -->
    <div class="mt-6 bg-gray-900 rounded-lg p-4 shadow-inner">
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-mono text-gray-400 uppercase tracking-widest">Logs Console</span>
            <span v-if="loading" class="animate-pulse text-blue-400 text-xs">● Processing</span>
        </div>
        <div class="h-64 overflow-y-auto font-mono text-sm">
            <div v-for="(l,i) in logs" :key="i" 
                 :class="l.includes('❌') ? 'text-red-400' : l.includes('✅') || l.includes('🎉') ? 'text-green-400' : 'text-gray-300'"
                 class="mb-1 border-l-2 border-gray-700 pl-2">
              <span class="text-gray-600 mr-2">[{{ new Date().toLocaleTimeString() }}]</span> {{ l }}
            </div>
            <div v-if="logs.length === 0" class="text-gray-600 italic">En attente de démarrage...</div>
        </div>
    </div>
  </div>
</template>