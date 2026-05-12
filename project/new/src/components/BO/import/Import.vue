<script setup lang="ts">
import { ref } from 'vue'
import { psGet, psPost, getXmlText } from '../../../utils/prestashop-api'

const loading = ref(false)
const logs = ref<string[]>([])
const referenceToTransform = ref('T_01')

/* ------------------ MAIN ------------------ */
async function triggerTransformation() {
    if (!referenceToTransform.value) {
        logs.value.unshift("⚠️ Référence manquante")
        return
    }

    loading.value = true
    logs.value.unshift(`🚀 Traitement : ${referenceToTransform.value}`)

    try {

        /* 1. GET PRODUIT */
        const res = await psGet('products', '', {
            display: 'full',
            'filter[reference]': `[${referenceToTransform.value}]`
        })

        const prodData = res?.prestashop?.products?.product
        const product = Array.isArray(prodData) ? prodData[0] : prodData

        if (!product) {
            throw new Error("Produit introuvable")
        }

        const productId = getXmlText(product.id)

        logs.value.unshift(`📦 Produit trouvé ID=${productId}`)

        /* 2. CREATE COMBINATION (BON ENDPOINT !) */

        const combinationXml = `
<prestashop>
  <combination>
    <id_product>${productId}</id_product>

    <reference><![CDATA[${referenceToTransform.value}_VAR1]]></reference>

    <price>0</price>
    <quantity>10</quantity>
    <default_on>0</default_on>
    <minimal_quantity>1</minimal_quantity>

    <associations>
      <product_option_values>
        <product_option_value>
          <id>1</id>
        </product_option_value>
        <product_option_value>
          <id>2</id>
        </product_option_value>
      </product_option_values>
    </associations>
  </combination>
</prestashop>`

        /* ✔ BON ENDPOINT (IMPORTANT) */
        await psPost('combinations', combinationXml)

        logs.value.unshift(`🎉 Déclinaison créée avec succès`)

    } catch (e: any) {
        console.error("ERROR:", e?.response?.data || e)

        logs.value.unshift(
            `❌ ERREUR : ${
                e?.response?.data?.prestashop?.errors?.error?.message
                || e.message
            }`
        )
    } finally {
        loading.value = false
    }
}
</script>
<template>
    <div class="p-6 max-w-xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Testeur de Transformation</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Référence du produit (ex: T_01, P_01)
                    </label>
                    <input v-model="referenceToTransform" type="text"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        placeholder="Entrez la référence..." />
                </div>

                <button @click="triggerTransformation" :disabled="loading"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition disabled:opacity-50">
                    {{ loading ? 'Transformation en cours...' : 'Transformer en produit avec variantes' }}
                </button>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-500 mb-2 uppercase tracking-wider">Logs de test</h3>
            <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-xs h-64 overflow-y-auto shadow-inner">
                <div v-for="(log, i) in logs" :key="i" class="mb-1 border-b border-gray-800 pb-1">
                    <span class="text-gray-500">>></span> {{ log }}
                </div>
                <div v-if="logs.length === 0" class="text-gray-600 italic">En attente d'action...</div>
            </div>
        </div>
    </div>
</template>