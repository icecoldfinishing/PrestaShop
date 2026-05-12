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

/* ---------------- DONNÉES ---------------- */
const loading = ref(false)
const logs = ref<string[]>([])

const rows = ref([
    { reference: 'T_01', specificite: 'taille', karazany: 'ngoza', stock: 13, prix: 12.5 },
    { reference: 'T_01', specificite: 'taille', karazany: 'kely', stock: 10, prix: 15 },
    { reference: 'P_01', specificite: 'couleur', karazany: 'mainty', stock: 5, prix: 23.49 },
    { reference: 'P_01', specificite: 'couleur', karazany: 'fotsy', stock: 3, prix: 18.99 },
    { reference: 'C_03', specificite: '', karazany: '', stock: 10, prix: 0 },
    { reference: 'M_02', specificite: '', karazany: '', stock: 11, prix: 0 }
])

/* ---------------- API WRAPPERS ---------------- */
async function localPsGet(resource: string, id = '', params = {}) {
    const url = `${BASE_URL}/${id ? `${resource}/${id}` : resource}`
    const res = await axios.get(url, {
        params: { ws_key: API_KEY, output_format: 'XML', ...params },
        headers: { Accept: 'application/xml' }
    })
    return parser.parse(res.data)
}

async function localPsPost(resource: string, xml: string) {
    const res = await axios.post(`${BASE_URL}/${resource}`, xml, {
        params: { ws_key: API_KEY },
        headers: { 'Content-Type': 'application/xml', Accept: 'application/xml' }
    })
    return res.data 
}

async function localPsPut(resource: string, xml: string) {
    const res = await axios.put(`${BASE_URL}/${resource}`, xml, {
        params: { ws_key: API_KEY },
        headers: { 'Content-Type': 'application/xml', Accept: 'application/xml' }
    })
    return res.data
}

const getXmlId = (xml: string) => {
    if (typeof xml !== 'string') return null
    const d = new DOMParser().parseFromString(xml, "text/xml")
    return d.getElementsByTagName('id')[0]?.textContent || null
}

const getXmlText = (value: any) => {
    if (!value) return ''
    return typeof value === 'object' ? value['#text'] ?? String(value) : String(value)
}

/* ---------------- LOGIQUE MÉTIER ---------------- */

async function getDynamicTaxRate(taxRulesGroupId: string) {
    try {
        const resRules = await localPsGet('tax_rules', '', { 
            display: '[id_tax]', 
            'filter[id_tax_rules_group]': `[${taxRulesGroupId}]` 
        })
        const rule = resRules?.prestashop?.tax_rules?.tax_rule
        const idTax = Array.isArray(rule) ? getXmlText(rule[0].id_tax) : getXmlText(rule?.id_tax)

        if (!idTax) return 1.20 // Fallback si vraiment rien

        const resTax = await localPsGet('taxes', idTax)
        const rate = parseFloat(getXmlText(resTax?.prestashop?.tax?.rate) || '20')
        return 1 + (rate / 100)
    } catch {
        return 1.20
    }
}

async function ensureAttrAndVal(spec: string, val: string) {
    let resGroup = await localPsGet('product_options', '', { display: 'full', 'filter[name]': spec })
    let groupData = resGroup?.prestashop?.product_options?.product_option
    let group = Array.isArray(groupData) ? groupData[0] : groupData
    let groupId = group ? getXmlText(group.id) : null

    if (!groupId) {
        const xml = `<prestashop><product_option>
            <name><language id="1">${spec}</language></name>
            <public_name><language id="1">${spec}</language></public_name>
            <group_type>select</group_type>
        </product_option></prestashop>`
        groupId = getXmlId(await localPsPost('product_options', xml))
        log(`🛠 Spec créée : ${spec}`)
    }

    let resVal = await localPsGet('product_option_values', '', { 
        display: 'full', 'filter[name]': val, 'filter[id_attribute_group]': groupId 
    })
    let valData = resVal?.prestashop?.product_option_values?.product_option_value
    let valId = Array.isArray(valData) ? getXmlText(valData[0].id) : (valData ? getXmlText(valData.id) : null)

    if (!valId) {
        const xml = `<prestashop><product_option_value>
            <id_attribute_group>${groupId}</id_attribute_group>
            <name><language id="1">${val}</language></name>
        </product_option_value></prestashop>`
        valId = getXmlId(await localPsPost('product_option_values', xml))
        log(` ✨ Valeur créée : ${val}`)
    }
    return valId
}

async function setStock(productId: string, combinationId: string, qty: number) {
    try {
        // 1. Attente pour laisser à PrestaShop le temps de générer la ligne de stock
        await sleep(1000);

        // 2. Recherche de la ligne de stock spécifique
        const res = await localPsGet('stock_availables', '', {
            'filter[id_product]': `[${productId}]`,
            'filter[id_product_attribute]': `[${combinationId}]`,
            display: '[id]' 
        })

        const stockData = res?.prestashop?.stock_availables?.stock_available
        // Gestion du cas où PS renvoie un tableau ou un objet seul
        const stock = Array.isArray(stockData) ? stockData[0] : stockData
        
        const stockId = getXmlText(stock?.id)

        if (!stockId) {
            log(`⚠️ Stock non trouvé pour Comb: ${combinationId}, tentative de rafraîchissement...`)
            // Petit retry interne si nécessaire
            return; 
        }

        // 3. Construction du XML ultra-propre
        // On s'assure que toutes les valeurs sont des strings simples
        const xml = `<prestashop>
            <stock_available>
                <id>${stockId}</id>
                <id_product>${getXmlText(productId)}</id_product>
                <id_product_attribute>${getXmlText(combinationId)}</id_product_attribute>
                <quantity>${qty}</quantity>
                <id_shop>1</id_shop>
                <id_shop_group>0</id_shop_group>
                <depends_on_stock>0</depends_on_stock>
                <out_of_stock>2</out_of_stock>
            </stock_available>
        </prestashop>`

        // 4. Envoi de la mise à jour
        await localPsPut('stock_availables', xml)
        log(`✅ STOCK MIS À JOUR : ${qty} (ID Stock: ${stockId})`)

    } catch (e: any) {
        // Extraction du message d'erreur précis de PrestaShop si disponible
        const detail = e.response?.data || e.message
        log(`❌ Erreur stock: ${detail}`)
        console.error("Détail Erreur 400:", detail)
    }
}

const log = (m: string) => logs.value.unshift(m)

/* ---------------- EXECUTION ---------------- */
async function startImport() {
    loading.value = true
    logs.value = ["🚀 Lancement de l'importation..."]

    try {
        for (const row of rows.value) {
            log(`--- Produit: ${row.reference} ---`)

            const resP = await localPsGet('products', '', { 
                display: '[id,price,id_tax_rules_group]', 
                'filter[reference]': `[${row.reference}]` 
            })
            const p = resP?.prestashop?.products?.product
            const product = Array.isArray(p) ? p[0] : p

            if (!product) {
                log(`❌ Référence ${row.reference} introuvable.`)
                continue
            }

            const pId = getXmlText(product.id)
            const taxGroupId = getXmlText(product.id_tax_rules_group)
            
            // RÉCUPÉRATION DU TAUX DYNAMIQUE
            const taxMultiplier = await getDynamicTaxRate(taxGroupId)
            log(`📊 Taxe détectée : ${((taxMultiplier - 1) * 100).toFixed(2)}%`)

            if (row.specificite && row.karazany) {
                const vId = await ensureAttrAndVal(row.specificite, row.karazany)
                
                const basePriceHT = parseFloat(getXmlText(product.price) || '0')
                const targetHT = row.prix / taxMultiplier
                const impactHT = targetHT - basePriceHT

                const xmlComb = `
<prestashop>
  <combination>
    <id_product>${pId}</id_product>
    <reference>${row.reference}_${row.karazany.replace(/\s+/g, '_')}</reference>
    <price>${impactHT.toFixed(6)}</price>
    <minimal_quantity>1</minimal_quantity>
    <associations>
      <product_option_values>
        <product_option_value><id>${vId}</id></product_option_value>
      </product_option_values>
    </associations>
  </combination>
</prestashop>`
                
                const combId = getXmlId(await localPsPost('combinations', xmlComb))
                log(`✅ Déclinaison créée (Impact HT: ${impactHT.toFixed(4)})`)
                await setStock(pId, combId!, row.stock)
            } else {
                log(`ℹ️ Produit simple : mise à jour stock`)
                await setStock(pId, '0', row.stock)
            }
        }
        log("🎉 TOUT EST TERMINÉ !")
    } catch (e: any) { log(`❌ ERREUR: ${e.message}`)
    } finally { loading.value = false }
}
</script>

<template>
    <div class="p-6 max-w-4xl mx-auto bg-slate-50 min-h-screen">
        <div class="bg-white p-8 rounded-2xl shadow-xl border">
            <h1 class="text-2xl font-bold mb-6">Importateur PrestaShop Dynamique</h1>

            <div class="space-y-1 mb-6">
                <div v-for="(r, i) in rows" :key="i" class="grid grid-cols-5 gap-2 p-2 border rounded text-[10px] bg-gray-50">
                    <span class="font-bold">{{ r.reference }}</span>
                    <span class="text-gray-400">{{ r.specificite || '---' }}</span>
                    <span>{{ r.karazany || '---' }}</span>
                    <span>Stock: {{ r.stock }}</span>
                    <span class="text-right font-bold text-indigo-600">{{ r.prix }}€</span>
                </div>
            </div>

            <button @click="startImport" :disabled="loading"
                class="w-full py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-lg active:scale-95">
                {{ loading ? 'Synchronisation...' : 'Démarrer l\'importation' }}
            </button>

            <div class="mt-8 bg-gray-900 rounded-xl p-5 h-80 overflow-y-auto font-mono text-[10px] text-indigo-300">
                <div v-for="(l, i) in logs" :key="i" class="mb-1 py-1 border-b border-gray-800">> {{ l }}</div>
            </div>
        </div>
    </div>
</template>