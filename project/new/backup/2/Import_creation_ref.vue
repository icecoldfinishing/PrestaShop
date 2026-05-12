<script setup lang="ts">
import { ref } from 'vue'
import { psPost, psGet, psPut, getXmlText } from '../../../utils/prestashop-api'

const loading = ref(false)
const logs = ref<string[]>([])

const csvData = [
    { ref: 'T_01', spec: 'taille', val: 'ngoza', stock: 13, prix: 12.5 },
    { ref: 'T_01', spec: 'taille', val: 'kely', stock: 10, prix: 15 },
    { ref: 'P_01', spec: 'couleur', val: 'mainty', stock: 5, prix: 23.49 },
    { ref: 'P_01', spec: 'couleur', val: 'fotsy', stock: 3, prix: 18.99 },
    { ref: 'C_03', spec: '', val: '', stock: 10, prix: 5 },
    { ref: 'M_02', spec: '', val: '', stock: 11, prix: 56 }
]

/* --- HELPERS --- */
const getXmlId = (xml: any) => {
    if (typeof xml !== 'string') return null
    const d = new DOMParser().parseFromString(xml, "text/xml")
    return d.getElementsByTagName('id')[0]?.textContent || null
}

async function getOrCreateAttribute(groupName: string, valueName: string) {
    if (!groupName || !valueName) return null
    
    // Recherche Groupe
    const resGroup = await psGet('product_options', '', { display: 'full', 'filter[name]': `[${groupName}]` })
    const groupData = resGroup?.prestashop?.product_options?.product_option
    const group = Array.isArray(groupData) ? groupData[0] : groupData
    let groupId = group ? getXmlText(group.id) : null

    if (!groupId) {
        const xml = `<prestashop><product_option><group_type>select</group_type><name><language id="1"><![CDATA[${groupName}]]></language></name><public_name><language id="1"><![CDATA[${groupName}]]></language></public_name></product_option></prestashop>`
        groupId = getXmlId(await psPost('product_options', xml))
    }

    // Recherche Valeur
    const resVal = await psGet('product_option_values', '', { display: 'full', 'filter[name]': `[${valueName}]`, 'filter[id_attribute_group]': `[${groupId}]` })
    const valData = resVal?.prestashop?.product_option_values?.product_option_value
    const val = Array.isArray(valData) ? valData[0] : valData
    let valId = val ? getXmlText(val.id) : null

    if (!valId) {
        const xml = `<prestashop><product_option_value><id_attribute_group>${groupId}</id_attribute_group><name><language id="1"><![CDATA[${valueName}]]></language></name></product_option_value></prestashop>`
        valId = getXmlId(await psPost('product_option_values', xml))
    }
    return valId
}

async function startImport() {
    loading.value = true
    logs.value = []

    const grouped = csvData.reduce((acc: any, item) => {
        if (!acc[item.ref]) acc[item.ref] = []
        acc[item.ref].push(item)
        return acc
    }, {})

    for (const ref in grouped) {
        try {
            const items = grouped[ref]
            const hasVariants = items.some(i => i.spec !== '')
            
            // 1. Gérer le Parent
            let resProd = await psGet('products', '', { display: 'full', 'filter[reference]': `[${ref}]` })
            let prodData = resProd?.prestashop?.products?.product
            let prod = Array.isArray(prodData) ? prodData[0] : prodData
            let prodId = prod ? getXmlText(prod.id) : null

            const basePriceTTC = items[0].prix // On prend le 1er prix comme base
            const basePriceHT = (basePriceTTC / 1.2).toFixed(6)

            if (!prodId) {
                const xml = `<prestashop><product>
                    <id_shop_default>1</id_shop_default>
                    <active>1</active>
                    <reference>${ref}</reference>
                    <price>${basePriceHT}</price>
                    <name><language id="1"><![CDATA[Produit ${ref}]]></language></name>
                    <link_rewrite><language id="1"><![CDATA[${ref.toLowerCase()}]]></language></link_rewrite>
                </product></prestashop>`
                prodId = getXmlId(await psPost('products', xml))
                logs.value.push(`📦 Nouveau produit ${ref} créé (ID: ${prodId})`)
            }

            // 2. Transformation en mode variantes si nécessaire
            if (hasVariants) {
                const transformXml = `<prestashop><product>
                    <id>${prodId}</id>
                    <cache_default_attribute>0</cache_default_attribute>
                    <type>1</type> </product></prestashop>`
                await psPut('products', transformXml)
            }

            for (const item of items) {
                if (hasVariants && item.spec) {
                    const valId = await getOrCreateAttribute(item.spec, item.val)
                    
                    // Calcul de l'impact de prix (Différence entre variante et base)
                    const impactHT = ((item.prix - basePriceTTC) / 1.2).toFixed(6)

                    const combXml = `<prestashop><combination>
                        <id_product>${prodId}</id_product>
                        <reference>${item.ref}-${item.val}</reference>
                        <price>${impactHT}</price>
                        <associations>
                            <product_option_values><product_option_value><id>${valId}</id></product_option_value></product_option_values>
                        </associations>
                    </combination></prestashop>`
                    
                    const combId = getXmlId(await psPost('combinations', combXml))
                    
                    // Mise à jour stock spécifique à la variante
                    const resStock = await psGet('stock_availables', '', { 'filter[id_product]': `[${prodId}]`, 'filter[id_product_attribute]': `[${combId}]` })
                    const stockId = getXmlText((Array.isArray(resStock?.prestashop?.stock_availables?.stock_available) ? resStock.prestashop.stock_availables.stock_available[0] : resStock?.prestashop?.stock_availables?.stock_available)?.id)
                    
                    if (stockId) {
                        await psPut('stock_availables', `<prestashop><stock_available><id>${stockId}</id><id_product>${prodId}</id_product><id_product_attribute>${combId}</id_product_attribute><quantity>${item.stock}</quantity><depends_on_stock>0</depends_on_stock></stock_available></prestashop>`)
                    }
                    logs.value.push(`  🔹 Variante ${item.val} : Impact prix ${impactHT}€`)
                } else {
                    // Produit Simple : mise à jour stock global
                    const resStock = await psGet('stock_availables', '', { 'filter[id_product]': `[${prodId}]`, 'filter[id_product_attribute]': '[0]' })
                    const stockId = getXmlText((Array.isArray(resStock?.prestashop?.stock_availables?.stock_available) ? resStock.prestashop.stock_availables.stock_available[0] : resStock?.prestashop?.stock_availables?.stock_available)?.id)
                    await psPut('stock_availables', `<prestashop><stock_available><id>${stockId}</id><id_product>${prodId}</id_product><id_product_attribute>0</id_product_attribute><quantity>${item.stock}</quantity></stock_available></prestashop>`)
                    logs.value.push(`  ✅ Stock simple mis à jour : ${item.stock}`)
                }
            }
        } catch (e: any) {
            logs.value.push(`❌ Erreur ${ref}: ${e.message}`)
        }
    }
    loading.value = false
}
</script>
<template>
    <div class="p-6">
        <button @click="startImport" :disabled="loading" class="bg-blue-700 text-white px-5 py-2 rounded shadow">
            {{ loading ? 'Importation...' : 'Lancer Import Variantes' }}
        </button>
        <div class="mt-4 bg-slate-900 text-slate-300 p-4 font-mono text-xs h-96 overflow-y-auto rounded shadow-inner">
            <div v-for="log in logs" :key="log" class="mb-1 border-b border-slate-800 pb-1">
                {{ log }}
            </div>
        </div>
    </div>
</template>