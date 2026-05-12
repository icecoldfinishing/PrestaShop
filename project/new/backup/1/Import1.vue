<script setup lang="ts">
import { ref } from 'vue'
import { psPost, psGet } from '../../../utils/prestashop-api'

const loading = ref(false)
const logs = ref<string[]>([])

const products = [
    { nom: 'Tshirt', reference: 'T_01', prix_ttc: 12.5, taxe: 11.65, prix_achat: 8.5 },
    { nom: 'Pantalon', reference: 'P_01', prix_ttc: 18.99, taxe: 11.65, prix_achat: 14.33 },
    { nom: 'Casquette', reference: 'C_03', prix_ttc: 5, taxe: 5.6, prix_achat: 2 },
    { nom: 'Montre', reference: 'M_02', prix_ttc: 56, taxe: 5.6, prix_achat: 40 }
]

/* --- PARSER XML --- */
function getXmlId(xmlString: string): string | null {
    const parser = new DOMParser()
    const xmlDoc = parser.parseFromString(xmlString, "text/xml")
    const idElement = xmlDoc.getElementsByTagName('id')[0]
    return idElement ? idElement.textContent : null
}

/* --- WORKFLOW TAXE --- */
async function getOrCreateTaxRuleGroup(rate: number) {
    const groupName = `TVA ${rate}%`
    
    // Recherche filtrée
    const rawList = await psGet('tax_rule_groups', '', { display: 'full', 'filter[name]': groupName })
    const parser = new DOMParser()
    const xmlDoc = parser.parseFromString(rawList, "text/xml")
    const existingGroup = xmlDoc.getElementsByTagName('tax_rule_group')[0]

    if (existingGroup) {
        return existingGroup.getElementsByTagName('id')[0].textContent
    }

    // Création Taxe brute
    const taxXml = `<prestashop><tax>
        <rate>${rate}</rate>
        <active>1</active>
        <name><language id="1"><![CDATA[${groupName}]]></language></name>
    </tax></prestashop>`
    const taxId = getXmlId(await psPost('taxes', taxXml))

    // Création Groupe
    const groupXml = `<prestashop><tax_rule_group>
        <name><![CDATA[${groupName}]]></name>
        <active>1</active>
    </tax_rule_group></prestashop>`
    const taxGroupId = getXmlId(await psPost('tax_rule_groups', groupXml))

    // Liaison Règle (ID 8 = France)
    const ruleXml = `<prestashop><tax_rule>
        <id_tax_rules_group>${taxGroupId}</id_tax_rules_group>
        <id_country>8</id_country>
        <id_tax>${taxId}</id_tax>
    </tax_rule></prestashop>`
    await psPost('tax_rules', ruleXml)

    return taxGroupId
}

/* --- IMPORTATION PRODUITS --- */
async function runImport() {
    loading.value = true
    logs.value = []

    for (const p of products) {
        try {
            const taxGroupId = await getOrCreateTaxRuleGroup(p.taxe)

            const prixHT = p.prix_ttc / (1 + p.taxe / 100)
            const slug = p.nom.toLowerCase().replace(/[^a-z0-9]/g, '-')

            const productXml = `<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
                <product>
                    <id_shop_default>1</id_shop_default>
                    <id_category_default>2</id_category_default>
                    <active>1</active>
                    <state>1</state>
                    <visibility>both</visibility>
                    
                    <id_tax_rules_group>${taxGroupId}</id_tax_rules_group>
                    
                    <price>${prixHT.toFixed(6)}</price>
                    <wholesale_price>${p.prix_achat.toFixed(6)}</wholesale_price>
                    
                    <reference>${p.reference}</reference>
                    <name><language id="1"><![CDATA[${p.nom}]]></language></name>
                    <link_rewrite><language id="1"><![CDATA[${slug}]]></language></link_rewrite>
                    
                    <available_for_order>1</available_for_order>
                    <show_price>1</show_price>
                    <minimal_quantity>1</minimal_quantity>
                    <condition>new</condition>
                    <indexed>1</indexed>
                    
                    <associations>
                        <categories>
                            <category><id>2</id></category>
                        </categories>
                    </associations>
                </product>
            </prestashop>`

            await psPost('products', productXml)

            logs.value.push(`✅ ${p.nom} importé (TVA ${p.taxe}% - Groupe ${taxGroupId})`)
        } catch (e: any) {
            console.error(e)
            logs.value.push(`❌ ${p.nom} : ${e.message || e}`)
        }
    }

    loading.value = false
    logs.value.push('--- Import terminé. Actualise le Back Office ---')
}
</script>

<template>
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Final Import : Fix Tax Overriding</h2>
    <button @click="runImport" :disabled="loading" class="bg-blue-600 text-white px-6 py-2 rounded">
        {{ loading ? 'Importation...' : 'Lancer' }}
    </button>
    <div class="mt-4 space-y-1">
        <div v-for="(log, i) in logs" :key="i" class="p-2 bg-gray-50 border-l-4 border-blue-500 font-mono text-xs">
            {{ log }}
        </div>
    </div>
</div>
</template>