<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { XMLParser } from 'fast-xml-parser'

/* ---------------- CONFIG API ---------------- */
const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

const parser = new XMLParser({
    ignoreAttributes: false,
    attributeNamePrefix: '@_',
})

/* ---------------- DATA DE TEST ---------------- */
const TEST_DATA = [
    {
        date: "09/05/2026",
        nom: "Rakoto",
        email: "rakoto@yopmail.com",
        pwd: "XvzsX5O0!GBD0uXQ",
        adresse: "Andoharanofotsy",
        achats: [{ ref: "T_01", qty: 3, declinaison: "ngoza" }],
        etat: "en attente paiement à la livraison"
    },
    {
        date: "16/04/2026",
        nom: "Rajao",
        email: "rajao1970@yopmail.com",
        pwd: "BAC?UoxjQIW;Na8ix",
        adresse: "Analakely",
        achats: [
            { ref: "T_01", qty: 2, declinaison: "kely" },
            { ref: "C_03", qty: 1, declinaison: "" }
        ],
        etat: "paiement accepté"
    }
];

/* ---------------- STATE ---------------- */
const logs = ref<string[]>([]);
const loading = ref(false);
const log = (m: string) => logs.value.push(`[${new Date().toLocaleTimeString()}] ${m}`);

/* ---------------- API ENGINE ---------------- */
async function psRequest(method: 'get' | 'post' | 'put', resource: string, xml?: string, params = {}) {
    const config = {
        params: { ws_key: API_KEY, output_format: 'XML', ...params },
        headers: { 'Content-Type': 'application/xml' }
    };
    const url = resource.startsWith('http') ? resource : `${BASE_URL}/${resource}`;
    const res = method === 'get' 
        ? await axios.get(url, config)
        : await axios[method](url, xml, config);
    return parser.parse(res.data);
}

/* ---------------- WORKFLOW ERP ---------------- */
async function runTest() {
    loading.value = true;
    logs.value = [];
    log("🚀 Démarrage de la pipeline ERP...");

    for (const entry of TEST_DATA) {
        try {
            log(`--- TRAITEMENT : ${entry.email} ---`);

            // 1. CUSTOMER (Récupération ou Création)
            let custId;
            let secureKey;
            const searchCust = await psRequest('get', 'customers', '', { 'filter[email]': `[${entry.email}]` });
            
            if (searchCust.prestashop.customers?.customer) {
                custId = searchCust.prestashop.customers.customer['@_id'];
                // Récupération de la secure_key pour l'existant
                const existing = await psRequest('get', `customers/${custId}`);
                secureKey = existing.prestashop.customer.secure_key;
                log(`STEP 1: Client existant (ID: ${custId})`);
            } else {
                const xmlCust = `<prestashop><customer><lastname>${entry.nom}</lastname><firstname>${entry.nom}</firstname><email>${entry.email}</email><passwd>${entry.pwd}</passwd><active>1</active></customer></prestashop>`;
                const resCust = await psRequest('post', 'customers', xmlCust);
                custId = resCust.prestashop.customer.id;
                secureKey = resCust.prestashop.customer.secure_key;
                log(`STEP 1: Client créé (ID: ${custId})`);
            }

            // 2. ADDRESS
            const xmlAddr = `<prestashop><address><id_customer>${custId}</id_customer><id_country>1</id_country><alias>ERP_IMPORT</alias><lastname>${entry.nom}</lastname><firstname>${entry.nom}</firstname><address1>${entry.adresse}</address1><city>Antananarivo</city><postcode>101</postcode></address></prestashop>`;
            const resAddr = await psRequest('post', 'addresses', xmlAddr);
            const addrId = resAddr.prestashop.address.id;
            log(`STEP 2: Adresse créée (ID: ${addrId})`);

            // 3. PRODUCTS & CART
            let cartRows = "";
            let totalOrder = 0;

            for (const item of entry.achats) {
                const pData = await psRequest('get', 'products', '', { 'filter[reference]': `[${item.ref}]`, display: 'full' });
                const product = pData.prestashop.products?.product;
                if (!product) throw new Error(`Produit ${item.ref} introuvable`);
                
                const pid = product.id;
                const price = parseFloat(product.price);
                
                let aid = 0;
                if (item.declinaison) {
                    const combRef = `${item.ref}_${item.declinaison}`;
                    const cData = await psRequest('get', 'combinations', '', { 'filter[reference]': `[${combRef}]` });
                    aid = cData.prestashop.combinations?.combination?.['@_id'] || 0;
                }

                cartRows += `<cart_row><id_product>${pid}</id_product><id_product_attribute>${aid}</id_product_attribute><quantity>${item.qty}</quantity></cart_row>`;
                totalOrder += (price * item.qty);
            }

            const xmlCart = `<prestashop><cart><id_customer>${custId}</id_customer><id_currency>1</id_currency><id_lang>1</id_lang><id_address_delivery>${addrId}</id_address_delivery><id_address_invoice>${addrId}</id_address_invoice><associations><cart_rows>${cartRows}</cart_rows></associations></cart></prestashop>`;
            const resCart = await psRequest('post', 'carts', xmlCart);
            const cartId = resCart.prestashop.cart.id;
            log(`STEP 3/4: Panier créé (ID: ${cartId})`);

            // 4. ORDER (Correction erreur 500)
            const statesMap: any = { "en attente paiement à la livraison": 13, "paiement accepté": 2, "erreur de paiement": 8 };
            const statusId = statesMap[entry.etat] || 1;

            const xmlOrder = `<prestashop>
                <order>
                    <id_address_delivery>${addrId}</id_address_delivery>
                    <id_address_invoice>${addrId}</id_address_invoice>
                    <id_cart>${cartId}</id_cart>
                    <id_currency>1</id_currency>
                    <id_lang>1</id_lang>
                    <id_customer>${custId}</id_customer>
                    <id_carrier>1</id_carrier>
                    <current_state>${statusId}</current_state>
                    <module>ps_cashondelivery</module>
                    <payment>Cash on delivery</payment>
                    <secure_key>${secureKey}</secure_key>
                    <total_paid>${totalOrder.toFixed(2)}</total_paid>
                    <total_paid_real>${entry.etat === 'paiement accepté' ? totalOrder.toFixed(2) : '0.00'}</total_paid_real>
                    <total_products>${totalOrder.toFixed(2)}</total_products>
                    <total_products_wt>${totalOrder.toFixed(2)}</total_products_wt>
                    <conversion_rate>1</conversion_rate>
                </order>
            </prestashop>`;
            
            const resOrder = await psRequest('post', 'orders', xmlOrder);
            const orderId = resOrder.prestashop.order.id;
            log(`STEP 5: Commande créée (ID: ${orderId})`);

            // 5. ORDER HISTORY
            const xmlHis = `<prestashop><order_history><id_order>${orderId}</id_order><id_order_state>${statusId}</id_order_state></order_history></prestashop>`;
            await psRequest('post', 'order_histories', xmlHis);
            log(`STEP 6: Statut "${entry.etat}" validé.`);

            log(`✅ SUCCÈS pour ${entry.email}`);

        } catch (err: any) {
            const errorMsg = err.response?.data?.prestashop?.errors?.error?.message || err.message;
            log(`❌ ERREUR sur ${entry.email}: ${errorMsg}`);
        }
    }
    loading.value = false;
    log("🏁 Session terminée.");
}
</script>

<template>
    <div class="min-h-screen bg-black text-green-500 p-8 font-mono">
        <div class="max-w-4xl mx-auto border border-green-900 p-6 shadow-2xl bg-black">
            <h1 class="text-xl font-bold mb-4 border-b border-green-900 pb-2 text-green-400">
                PS_ERP_PIPELINE_STRICT
            </h1>

            <button 
                @click="runTest" 
                :disabled="loading"
                class="bg-green-900 hover:bg-green-700 text-white px-6 py-2 rounded mb-6 disabled:opacity-50"
            >
                {{ loading ? '> INITIALIZING...' : '> EXECUTE_INTERNAL_DATA' }}
            </button>

            <div class="bg-gray-950 p-4 rounded h-[450px] overflow-y-auto border border-green-900 shadow-inner">
                <div v-for="(log, i) in logs" :key="i" class="mb-1 text-sm">
                    <span :class="{
                        'text-red-500': log.includes('❌'),
                        'text-cyan-300': log.includes('✅'),
                        'text-yellow-500': log.includes('STEP')
                    }">{{ log }}</span>
                </div>
                <div v-if="logs.length === 0" class="text-gray-800 italic">Prêt pour injection de données...</div>
            </div>
        </div>
    </div>
</template>