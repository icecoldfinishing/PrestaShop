<script setup lang="ts">
import { ref } from 'vue'
import { psPost, psGet, psPut, getXmlText } from '../../../utils/prestashop-api'

const loading = ref(false)
const logs = ref<string[]>([])

// Données corrigées (prix avec point pour le JS)
const csvData = [
    { ref: 'T_01', spec: 'taille', val: 'ngoza', stock: 13, prix: 12.5 },
    { ref: 'T_01', spec: 'taille', val: 'kely', stock: 10, prix: 15 },
    { ref: 'P_01', spec: 'couleur', val: 'mainty', stock: 5, prix: 23.49 },
    { ref: 'P_01', spec: 'couleur', val: 'fotsy', stock: 3, prix: 18.99 },
    { ref: 'C_03', spec: '', val: '', stock: 10, prix: 5 },
    { ref: 'M_02', spec: '', val: '', stock: 11, prix: 56 }
]

/* --- HELPER POUR EXTRAIRE L'ID DU XML DE RETOUR (STRING) --- */
const getXmlId = (xmlString: string) => {
    if (typeof xmlString !== 'string') return null;
    const d = new DOMParser().parseFromString(xmlString, "text/xml")
    return d.getElementsByTagName('id')[0]?.textContent || null
}

/* =========================================================
   GESTION DES ATTRIBUTS (CHECK STRICT)
========================================================= */
async function getOrCreateAttribute(groupName: string, valueName: string) {
    if (!groupName || !valueName) return null

    // 1. CHERCHER LE GROUPE (Renvoie déjà un objet JSON via psGet)
    const resGroup = await psGet('product_options', '', { 
        display: 'full', 
        'filter[name]': `[${groupName}]` 
    })
    
    // Extraction sécurisée de l'ID depuis le JSON
    const groupData = resGroup?.prestashop?.product_options?.product_option;
    const group = Array.isArray(groupData) ? groupData[0] : groupData;
    let groupId = group ? getXmlText(group.id) : null;

    if (!groupId) {
        const xml = `<prestashop><product_option>
            <group_type><![CDATA[select]]></group_type>
            <name><language id="1"><![CDATA[${groupName}]]></language></name>
            <public_name><language id="1"><![CDATA[${groupName}]]></language></public_name>
        </product_option></prestashop>`
        groupId = getXmlId(await psPost('product_options', xml))
        console.log(`✅ Groupe créé : ${groupName} (ID: ${groupId})`)
    }

    // 2. CHERCHER LA VALEUR DANS CE GROUPE
    const resVal = await psGet('product_option_values', '', { 
        display: 'full', 
        'filter[name]': `[${valueName}]`,
        'filter[id_attribute_group]': `[${groupId}]` 
    })

    const valData = resVal?.prestashop?.product_option_values?.product_option_value;
    const val = Array.isArray(valData) ? valData[0] : valData;
    let valId = val ? getXmlText(val.id) : null;

    if (!valId) {
        const xml = `<prestashop><product_option_value>
            <id_attribute_group><![CDATA[${groupId}]]></id_attribute_group>
            <name><language id="1"><![CDATA[${valueName}]]></language></name>
        </product_option_value></prestashop>`
        valId = getXmlId(await psPost('product_option_values', xml))
        console.log(`✅ Valeur créée : ${valueName} (ID: ${valId})`)
    }

    return valId
}

/* =========================================================
   MISE À JOUR STOCK
========================================================= */
async function updateStock(prodId: string, combId: string, qty: number) {
    const res = await psGet('stock_availables', '', { 
        'filter[id_product]': `[${prodId}]`, 
        'filter[id_product_attribute]': `[${combId || 0}]` 
    })
    
    const stockData = res?.prestashop?.stock_availables?.stock_available;
    const stock = Array.isArray(stockData) ? stockData[0] : stockData;
    const stockId = stock ? getXmlText(stock.id) : null;
    
    if (stockId) {
        const xml = `<prestashop><stock_available>
            <id>${stockId}</id>
            <id_product>${prodId}</id_product>
            <id_product_attribute>${combId || 0}</id_product_attribute>
            <quantity>${qty}</quantity>
            <depends_on_stock>0</depends_on_stock>
            <out_of_stock>2</out_of_stock>
        </stock_available></prestashop>`
        await psPut('stock_availables', xml)
    }
}

/* =========================================================
   IMPORTATION
========================================================= */
async function startImport() {
    loading.value = true
    logs.value = []

    // Grouper par référence pour ne créer qu'un seul parent
    const grouped = csvData.reduce((acc: any, item) => {
        if (!acc[item.ref]) acc[item.ref] = []
        acc[item.ref].push(item)
        return acc
    }, {})

    for (const ref in grouped) {
        const items = grouped[ref]
        const hasVariants = items.some(i => i.spec !== '')

        try {
            // 1. Créer le Parent (Uniquement s'il n'existe pas ou par défaut ici)
            const slug = ref.toLowerCase().replace(/_/g, '-')
            const productXml = `<prestashop><product>
                <id_shop_default>1</id_shop_default>
                <active>1</active>
                <id_category_default>2</id_category_default>
                <name><language id="1"><![CDATA[Produit ${ref}]]></language></name>
                <link_rewrite><language id="1"><![CDATA[${slug}]]></language></link_rewrite>
                <reference>${ref}</reference>
                <price>0</price> 
            </product></prestashop>`
            
            const prodId = getXmlId(await psPost('products', productXml))
            logs.value.push(`📦 [${ref}] Parent créé (ID: ${prodId})`)

            for (const item of items) {
                if (hasVariants && item.spec) {
                    const valId = await getOrCreateAttribute(item.spec, item.val)
                    
                    const combXml = `<prestashop><combination>
                        <id_product>${prodId}</id_product>
                        <reference>${item.ref}-${item.val}</reference>
                        <price>${(item.prix / 1.2).toFixed(6)}</price>
                        <minimal_quantity>1</minimal_quantity>
                        <associations>
                            <product_option_values>
                                <product_option_value><id>${valId}</id></product_option_value>
                            </product_option_values>
                        </associations>
                    </combination></prestashop>`
                    
                    const combId = getXmlId(await psPost('combinations', combXml))
                    await updateStock(prodId, combId!, item.stock)
                    logs.value.push(`  🔹 Variante ${item.val} liée au groupe ${item.spec}`)
                } else {
                    // Produit simple
                    const prixHT = item.prix ? (item.prix / 1.2).toFixed(6) : "0"
                    await psPut('products', `<prestashop><product><id>${prodId}</id><price>${prixHT}</price></product></prestashop>`)
                    await updateStock(prodId, '0', item.stock)
                    logs.value.push(`  ✅ Produit simple stock mis à jour`)
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