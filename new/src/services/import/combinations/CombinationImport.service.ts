// On importe depuis ton fichier existant
import { psGet, psPost, psPut, getXmlId, getXmlText, sleep } from '../../prestashopApi.service';

export type CombinationRow = {
    reference: string;
    specificite: string;
    karazany: string;
    stock: number;
    prix: number;
};

async function getDynamicTaxRate(taxRulesGroupId: string): Promise<number> {
    try {
        const resRules: any = await psGet('tax_rules', '', { 
            display: '[id_tax]', 
            'filter[id_tax_rules_group]': `[${taxRulesGroupId}]` 
        });
        const rule = resRules?.prestashop?.tax_rules?.tax_rule;
        const idTax = Array.isArray(rule) ? getXmlText(rule[0].id_tax) : getXmlText(rule?.id_tax);

        if (!idTax) return 1.20;

        const resTax: any = await psGet('taxes', idTax);
        const rate = parseFloat(getXmlText(resTax?.prestashop?.tax?.rate) || '20');
        return 1 + (rate / 100);
    } catch {
        return 1.20;
    }
}

async function ensureAttrAndVal(spec: string, val: string, log: Function) {
    let resGroup: any = await psGet('product_options', '', { display: 'full', 'filter[name]': spec });
    let groupData = resGroup?.prestashop?.product_options?.product_option;
    let group = Array.isArray(groupData) ? groupData[0] : groupData;
    let groupId = group ? getXmlText(group.id) : null;

    if (!groupId) {
        const xml = `<prestashop><product_option>
            <name><language id="1">${spec}</language></name>
            <public_name><language id="1">${spec}</language></public_name>
            <group_type>select</group_type>
        </product_option></prestashop>`;
        groupId = getXmlId(await psPost('product_options', xml));
        log(`🛠 Spec créée : ${spec}`);
    }

    let resVal: any = await psGet('product_option_values', '', { 
        display: 'full', 'filter[name]': val, 'filter[id_attribute_group]': groupId 
    });
    let valData = resVal?.prestashop?.product_option_values?.product_option_value;
    let valId = Array.isArray(valData) ? getXmlText(valData[0].id) : (valData ? getXmlText(valData.id) : null);

    if (!valId) {
        const xml = `<prestashop><product_option_value>
            <id_attribute_group>${groupId}</id_attribute_group>
            <name><language id="1">${val}</language></name>
        </product_option_value></prestashop>`;
        valId = getXmlId(await psPost('product_option_values', xml));
        log(` ✨ Valeur créée : ${val}`);
    }
    return valId;
}

async function setStock(productId: string, combinationId: string, qty: number, log: Function) {
    try {
        // Un délai un peu plus long peut aider sur des serveurs lents
        await sleep(1500);

        const res: any = await psGet('stock_availables', '', {
            'filter[id_product]': `[${productId}]`,
            'filter[id_product_attribute]': `[${combinationId}]`,
            display: '[id]' 
        });

        const stockData = res?.prestashop?.stock_availables?.stock_available;
        const stockId = getXmlText(Array.isArray(stockData) ? stockData[0]?.id : stockData?.id);

        if (!stockId) {
            log(`⚠️ Stock ID non trouvé pour Prod:${productId} Comb:${combinationId}`);
            return;
        }

        // Nettoyage strict des IDs (force string et retire les espaces)
        const cleanProductId = String(productId).trim();
        const cleanCombId = String(combinationId).trim();
        const cleanStockId = String(stockId).trim();

        const xml = `<prestashop>
            <stock_available>
                <id>${cleanStockId}</id>
                <id_product>${cleanProductId}</id_product>
                <id_product_attribute>${cleanCombId}</id_product_attribute>
                <quantity>${qty}</quantity>
                <id_shop>1</id_shop>
                <id_shop_group>0</id_shop_group>
                <depends_on_stock>0</depends_on_stock>
                <out_of_stock>2</out_of_stock>
            </stock_available>
        </prestashop>`;

        await psPut('stock_availables', xml);
        log(`✅ STOCK MIS À JOUR : ${qty}`);
    } catch (e: any) {
        // On log le détail pour comprendre pourquoi PrestaShop rejette le XML
        const errorDetail = e.response?.data || e.message;
        log(`❌ Erreur Stock (400): Vérifiez les logs console`);
        console.error("Détail erreur 400 PS:", errorDetail);
    }
}

export async function runCombinationImport(rows: CombinationRow[], log: Function) {
    for (const row of rows) {
        log(`--- Produit: ${row.reference} ---`);
        const resP: any = await psGet('products', '', { 
            display: '[id,price,id_tax_rules_group]', 
            'filter[reference]': `[${row.reference}]` 
        });
        const product = Array.isArray(resP?.prestashop?.products?.product) 
            ? resP?.prestashop?.products?.product[0] 
            : resP?.prestashop?.products?.product;

        if (!product) {
            log(`❌ Référence ${row.reference} introuvable.`);
            continue;
        }

        const pId = getXmlText(product.id);
        const taxMultiplier = await getDynamicTaxRate(getXmlText(product.id_tax_rules_group));

        if (row.specificite && row.karazany) {
            const vId = await ensureAttrAndVal(row.specificite, row.karazany, log);
            const impactHT = (row.prix / taxMultiplier) - parseFloat(getXmlText(product.price));

            const xmlComb = `<prestashop><combination>
                <id_product>${pId}</id_product>
                <reference>${row.reference}_${row.karazany.replace(/\s+/g, '_')}</reference>
                <price>${impactHT.toFixed(6)}</price>
                <minimal_quantity>1</minimal_quantity>
                <associations><product_option_values>
                    <product_option_value><id>${vId}</id></product_option_value>
                </product_option_values></associations>
            </combination></prestashop>`;
            
            const combId = getXmlId(await psPost('combinations', xmlComb));
            log(`✅ Déclinaison créée`);
            await setStock(pId, combId!, row.stock, log);
        } else {
            await setStock(pId, '0', row.stock, log);
        }
    }
}