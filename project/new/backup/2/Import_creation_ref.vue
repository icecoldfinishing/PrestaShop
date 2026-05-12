<script setup lang="ts">
import { ref } from 'vue'
import { psPost, psGet, getXmlText } from '../../../utils/prestashop-api'

const loading = ref(false)
const logs = ref<string[]>([])

// Tes données sources
const csvData = [
    { spec: 'taille', val: 'ngoza' },
    { spec: 'taille', val: 'kely' },
    { spec: 'couleur', val: 'mainty' },
    { spec: 'couleur', val: 'fotsy' }
]

const getXmlId = (xml: string) => {
    const d = new DOMParser().parseFromString(xml, "text/xml")
    return d.getElementsByTagName('id')[0]?.textContent || null
}

/**
 * Fonction principale pour créer uniquement les attributs et valeurs
 */
async function createAttributesOnly() {
    loading.value = true
    logs.value = []

    for (const item of csvData) {
        if (!item.spec || !item.val) continue

        try {
            // 1. Gérer le GROUPE (ex: "taille")
            let resGroup = await psGet('product_options', '', { 
                display: 'full', 
                'filter[name]': item.spec 
            })
            
            let groupData = resGroup?.prestashop?.product_options?.product_option
            let group = Array.isArray(groupData) ? groupData[0] : groupData
            let groupId = group ? getXmlText(group.id) : null

            if (!groupId) {
                const xmlGroup = `<prestashop>
                    <product_option>
                        <name><language id="1">${item.spec}</language></name>
                        <public_name><language id="1">${item.spec}</language></public_name>
                        <group_type>select</group_type>
                    </product_option>
                </prestashop>`
                groupId = getXmlId(await psPost('product_options', xmlGroup))
                logs.value.push(`🛠 Groupe créé : ${item.spec} (ID: ${groupId})`)
            }

            // 2. Gérer la VALEUR (ex: "ngoza")
            const resVal = await psGet('product_option_values', '', { 
                display: 'full', 
                'filter[name]': item.val,
                'filter[id_attribute_group]': groupId
            })
            
            let valData = resVal?.prestashop?.product_option_values?.product_option_value
            let val = Array.isArray(valData) ? valData[0] : valData
            let valId = val ? getXmlText(val.id) : null

            if (!valId) {
                const xmlVal = `<prestashop>
                    <product_option_value>
                        <id_attribute_group>${groupId}</id_attribute_group>
                        <name><language id="1">${item.val}</language></name>
                    </product_option_value>
                </prestashop>`
                valId = getXmlId(await psPost('product_option_values', xmlVal))
                logs.value.push(`  ✨ Valeur ajoutée : ${item.val} (ID: ${valId})`)
            } else {
                logs.value.push(`  ✅ La valeur "${item.val}" existe déjà dans "${item.spec}"`)
            }

        } catch (e: any) {
            logs.value.push(`❌ Erreur sur ${item.spec}/${item.val}: ${e.message}`)
        }
    }
    loading.value = false
}
</script>

<template>
    <div class="p-6 text-slate-800">
        <h1 class="text-xl font-bold mb-4">Générateur d'Attributs & Valeurs</h1>
        
        <button @click="createAttributesOnly" :disabled="loading" 
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow transition-all disabled:opacity-50">
            {{ loading ? 'Création en cours...' : 'Lancer la création des Specs' }}
        </button>

        <div class="mt-6 bg-slate-900 text-green-400 p-4 font-mono text-xs h-80 overflow-y-auto rounded shadow-inner border border-slate-700">
            <div v-for="(log, i) in logs" :key="i" class="mb-1">
                <span class="text-slate-500 mr-2">></span> {{ log }}
            </div>
            <div v-if="logs.length === 0" class="text-slate-500 italic">Aucun log pour le moment.</div>
        </div>
    </div>
</template>