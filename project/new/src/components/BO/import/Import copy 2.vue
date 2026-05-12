<script setup lang="ts">
import { ref } from 'vue'
import { psGet, psPut, getXmlText } from '../../../utils/prestashop-api'

const loading = ref(false)
const logs = ref<string[]>([])
const referenceToTransform = ref('T_01')

/* --- TRANSFORMATION UNIQUE PAR RÉFÉRENCE --- */
async function transformExistingProduct() {
    if (!referenceToTransform.value) return
    
    loading.value = true
    logs.value.unshift(`🔍 Recherche du produit réf: ${referenceToTransform.value}...`)

    try {
        // 1. Chercher le produit existant pour obtenir son ID et ses données actuelles
        const resSearch = await psGet('products', '', { 
            display: 'full', 
            'filter[reference]': `[${referenceToTransform.value}]` 
        })
        
        const prodData = resSearch?.prestashop?.products?.product
        const existingProd = Array.isArray(prodData) ? prodData[0] : prodData
        
        if (!existingProd) {
            logs.value.unshift(`❌ Erreur : Aucun produit trouvé avec la référence ${referenceToTransform.value}`)
            loading.value = false
            return
        }

        const prodId = getXmlText(existingProd.id)
        const currentType = getXmlText(existingProd.type)

        // Si le produit est déjà en type 1 (déclinaisons), on s'arrête
        if (currentType === '1') {
            logs.value.unshift(`ℹ️ Le produit ${referenceToTransform.value} (ID: ${prodId}) est déjà de type "Déclinaisons".`)
            loading.value = false
            return
        }

        // 2. Modifier le type via psPut (Mise à jour)
        // Note : On renvoie les champs obligatoires pour éviter l'erreur 400
        const updateXml = `<prestashop>
            <product>
                <id>${prodId}</id>
                <type>1</type>
                <id_shop_default>1</id_shop_default>
                <id_category_default>${getXmlText(existingProd.id_category_default) || '2'}</id_category_default>
                <reference><![CDATA[${referenceToTransform.value}]]></reference>
                <name><language id="1"><![CDATA[${getXmlText(existingProd.name?.language)}]]></language></name>
                <link_rewrite><language id="1"><![CDATA[${getXmlText(existingProd.link_rewrite?.language)}]]></language></link_rewrite>
                <active>1</active>
                <state>1</state>
                <price>${getXmlText(existingProd.price)}</price>
            </product>
        </prestashop>`

        await psPut('products', updateXml)
        logs.value.unshift(`✅ SUCCESS : Le produit ${referenceToTransform.value} (ID: ${prodId}) est maintenant transformé.`)

    } catch (e: any) {
        console.error(e)
        logs.value.unshift(`❌ Erreur : ${e.message}`)
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="p-6 max-w-lg mx-auto">
        <div class="bg-white border rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold mb-4">Transformer Type Produit</h2>
            
            <div class="flex flex-col gap-4">
                <div>
                    <label class="text-sm font-medium gray-600">Référence du produit existant :</label>
                    <input 
                        v-model="referenceToTransform" 
                        type="text" 
                        class="w-full border rounded px-3 py-2 mt-1 outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <button 
                    @click="transformExistingProduct" 
                    :disabled="loading"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                    {{ loading ? 'Action en cours...' : 'Changer Type en "Déclinaisons"' }}
                </button>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Console de test</h3>
            <div class="bg-black text-green-400 p-4 rounded h-64 overflow-y-auto font-mono text-xs shadow-inner">
                <div v-for="(log, i) in logs" :key="i" class="mb-1">
                    <span class="text-gray-600">></span> {{ log }}
                </div>
            </div>
        </div>
    </div>
</template>