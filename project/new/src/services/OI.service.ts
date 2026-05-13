import { psGet, psPost, getXmlId, getXmlText } from './prestashopApi.service';

export interface OrderRow {
    date: string;
    nom: string;
    email: string;
    pwd: string;
    adresse: string;
    achat: string;
    etat: string;
}

/**
 * ÉTAPE 1 : Récupérer ou Créer le client
 */
/**
 * ÉTAPE 1 : Récupérer ou Créer le client (avec récupération de la secure_key)
 */
export async function ensureCustomer(row: OrderRow, log: Function): Promise<{ id: string, secure_key: string } | null> {
    log(`🔍 Vérification du client : ${row.email}`);

    try {
        // 1. Chercher si l'email existe déjà
        // On demande spécifiquement l'id et la secure_key pour l'étape Order
        const res: any = await psGet('customers', '', {
            'filter[email]': `[${row.email}]`,
            display: '[id,secure_key]'
        });

        const customerData = res?.prestashop?.customers?.customer;
        
        // PrestaShop retourne soit un tableau (si plusieurs résultats), soit un objet, soit rien
        let target = Array.isArray(customerData) ? customerData[0] : customerData;

        if (target && target.id) {
            const id = getXmlText(target.id);
            const secure_key = getXmlText(target.secure_key);
            log(`✅ Client trouvé (ID: ${id})`);
            return { id, secure_key };
        }

        // 2. Si non trouvé, on le crée
        log(`✨ Client inconnu. Création du nouveau compte...`);
        const xmlCustomer = `
        <prestashop>
            <customer>
                <lastname>${row.nom}</lastname>
                <firstname>Client</firstname>
                <email>${row.email}</email>
                <passwd>${row.pwd}</passwd>
                <active>1</active>
                <id_shop>1</id_shop>
                <id_default_group>3</id_default_group> 
            </customer>
        </prestashop>`;

        const newXml = await psPost('customers', xmlCustomer);
        const newId = getXmlId(newXml);

        // Après création, on refait un petit GET rapide pour chopper la secure_key générée par PS
        const verify: any = await psGet('customers', newId, { display: '[id,secure_key]' });
        const finalData = verify?.prestashop?.customer;

        log(`✅ Client créé avec succès (ID: ${newId})`);
        
        return { 
            id: newId, 
            secure_key: getXmlText(finalData?.secure_key) || "" 
        };

    } catch (error: any) {
        log(`❌ Erreur étape Client : ${error.message}`);
        console.error("Détail client:", error.response?.data || error);
        return null;
    }
}

/**
 * ÉTAPE 2 : Récupérer ou Créer l'adresse
 */
export async function ensureAddress(customerId: string, row: OrderRow, log: Function): Promise<string | null> {
    log(`🏠 Gestion de l'adresse pour le client ${customerId}...`);

    // 1. Chercher si le client a déjà une adresse avec ce nom de ville
    const res: any = await psGet('addresses', '', {
        'filter[id_customer]': `[${customerId}]`,
        'filter[city]': `[${row.adresse}]`,
        display: '[id]'
    });

    const addrData = res?.prestashop?.addresses?.address;
    let addressId = Array.isArray(addrData) ? getXmlText(addrData[0]?.id) : getXmlText(addrData?.id);

    if (addressId) {
        log(`✅ Adresse trouvée (ID: ${addressId})`);
        return addressId;
    }

    // 2. Sinon, on crée l'adresse
    log(`✨ Création d'une nouvelle adresse : ${row.adresse}`);
    const xmlAddress = `
    <prestashop>
        <address>
            <id_customer>${customerId}</id_customer>
            <id_country>1</id_country> <!-- 1 par défaut (souvent France ou pays de base), à adapter selon ton PS -->
            <alias>Mon Adresse</alias>
            <lastname>${row.nom}</lastname>
            <firstname>Client</firstname>
            <address1>${row.adresse}</address1>
            <city>${row.adresse}</city>
            <postcode>101</postcode> <!-- Code postal par défaut -->
        </address>
    </prestashop>`;

    try {
        const newXml = await psPost('addresses', xmlAddress);
        addressId = getXmlId(newXml);
        log(`✅ Adresse créée (ID: ${addressId})`);
        return addressId;
    } catch (error: any) {
        log(`❌ Erreur création adresse : ${error.message}`);
        return null;
    }
}

/**
 * Parser spécial pour le format : [(""REF"";QTY;""ATTR"")]
 */
function parseAchat(achatStr: string) {
    const normalized = achatStr.replace(/""/g, '"');
    const regex = /\("([^"]+)"\s*;\s*(\d+)\s*;\s*"([^"]*)"\)/g;
    const items: { ref: string; qty: string; attr: string }[] = [];
    let match;

    while ((match = regex.exec(normalized)) !== null) {
        items.push({
            ref: match[1],
            qty: match[2],
            attr: match[3],
        });
    }

    return items;
}

/**
 * ÉTAPE 3 : Créer le Panier (Cart)
 */
export async function createCart(
    customerId: string,
    addressId: string,
    row: OrderRow,
    log: Function
): Promise<{ cartId: string; total: string } | null> {
    log(`🛒 Analyse des articles pour ${row.nom}...`);

    const items = parseAchat(row.achat);
    if (items.length === 0) {
        log(`⚠️ Aucun article trouvé dans la colonne achat.`);
        return null;
    }

    let cartRowsXml = '';
    let total = 0;

    for (const item of items) {
        // 1. Trouver le produit par sa référence
        const resP: any = await psGet('products', '', {
            'filter[reference]': `[${item.ref}]`,
            display: '[id,price]'
        });
        const product = Array.isArray(resP?.prestashop?.products?.product)
            ? resP?.prestashop?.products?.product[0]
            : resP?.prestashop?.products?.product;
        const pId = getXmlText(product?.id);
        const price = Number.parseFloat(getXmlText(product?.price) || '0');

        if (!pId) {
            log(`❌ Produit ${item.ref} introuvable.`);
            continue;
        }

        // 2. Trouver l'ID de la déclinaison (si attr existe)
        let idProductAttribute = '0';
        if (item.attr) {
            log(`🔍 Cherche déclinaison "${item.attr}" pour le produit ${pId}...`);
            // On récupère toutes les combinaisons du produit
            const resC: any = await psGet('combinations', '', { 'filter[id_product]': `[${pId}]`, display: 'full' });
            const combinations = Array.isArray(resC?.prestashop?.combinations?.combination) 
                ? resC?.prestashop?.combinations?.combination 
                : [resC?.prestashop?.combinations?.combination];

            // Ici, on fait une recherche simplifiée sur le premier ID trouvé 
            // car le nom "ngoza" nécessite normalement une jointure complexe sur product_option_values.
            // Pour tester l'import, on prend la première combinaison trouvée.
            if (combinations[0]) {
                idProductAttribute = getXmlText(combinations[0].id);
                log(`📍 Déclinaison mappée sur ID: ${idProductAttribute}`);
            }
        }

        const qty = Number.parseInt(item.qty, 10) || 0;
        total += price * qty;

        cartRowsXml += `
        <cart_row>
            <id_product>${pId}</id_product>
            <id_product_attribute>${idProductAttribute}</id_product_attribute>
            <id_address_delivery>${addressId}</id_address_delivery>
            <quantity>${item.qty}</quantity>
        </cart_row>`;
    }

    if (!cartRowsXml) {
        log(`❌ Aucun article valide pour ${row.nom}.`);
        return null;
    }

    const xmlCart = `
    <prestashop>
        <cart>
            <id_customer>${customerId}</id_customer>
            <id_address_delivery>${addressId}</id_address_delivery>
            <id_address_invoice>${addressId}</id_address_invoice>
            <id_currency>1</id_currency>
            <id_lang>1</id_lang>
            <id_shop>1</id_shop>
            <id_shop_group>1</id_shop_group>
            <associations>
                <cart_rows>${cartRowsXml}</cart_rows>
            </associations>
        </cart>
    </prestashop>`;

    try {
        const newXml = await psPost('carts', xmlCart);
        const cartId = getXmlId(newXml);
        log(`✅ Panier créé avec succès (ID: ${cartId})`);
        return { cartId, total: total.toFixed(6) };
    } catch (e: any) {
        log(`❌ Erreur Panier: ${e.message}`);
        return null;
    }
}


export async function createOrder(
    cartInfo: { cartId: string; total: string },
    customerId: string,
    addressId: string,
    row: OrderRow,
    log: Function,
    customerKey: string = ""
): Promise<string | null> {

    log(`💸 Création commande sécurisée...`);

    const statusMapping: Record<string, string> = {
        "paiement accepté": "2",
        "en attente paiement à la livraison": "3",
        "en attente paiement": "1",
        "erreur de paiement": "8"
    };

    try {

                const safeTotal = cartInfo.total || "1.000000";

        const xmlOrder = `
<prestashop>
  <order>
    <id_address_delivery>${addressId}</id_address_delivery>
    <id_address_invoice>${addressId}</id_address_invoice>
    <id_cart>${cartInfo.cartId}</id_cart>
    <id_currency>1</id_currency>
    <id_lang>1</id_lang>
    <id_customer>${customerId}</id_customer>
    <id_carrier>1</id_carrier>
    <id_shop>1</id_shop>
    <id_shop_group>1</id_shop_group>

    <current_state>${statusMapping[row.etat] || "1"}</current_state>

        <module>ps_checkpayment</module>
        <payment>Import</payment>

        <valid>0</valid>
        <invoice_number>0</invoice_number>
        <delivery_number>0</delivery_number>

        <recyclable>0</recyclable>
        <gift>0</gift>
        <gift_message></gift_message>
        <mobile_theme>0</mobile_theme>

    <!-- 🔥 OBLIGATOIRE: valeurs cohérentes -->
    <total_paid>${safeTotal}</total_paid>
    <total_paid_real>${safeTotal}</total_paid_real>
    <total_paid_tax_incl>${safeTotal}</total_paid_tax_incl>
    <total_paid_tax_excl>${safeTotal}</total_paid_tax_excl>

    <total_products>${safeTotal}</total_products>
    <total_products_wt>${safeTotal}</total_products_wt>

    <total_shipping>0</total_shipping>
    <total_shipping_tax_incl>0</total_shipping_tax_incl>
    <total_shipping_tax_excl>0</total_shipping_tax_excl>

        <total_discounts>0</total_discounts>
        <total_discounts_tax_incl>0</total_discounts_tax_incl>
        <total_discounts_tax_excl>0</total_discounts_tax_excl>

        <total_wrapping>0</total_wrapping>
        <total_wrapping_tax_incl>0</total_wrapping_tax_incl>
        <total_wrapping_tax_excl>0</total_wrapping_tax_excl>

        <carrier_tax_rate>0</carrier_tax_rate>

        <conversion_rate>1</conversion_rate>

        <round_mode>2</round_mode>
        <round_type>2</round_type>

    <secure_key>${customerKey}</secure_key>
  </order>
</prestashop>`;

        const res = await psPost('orders', xmlOrder)
        const orderId = getXmlId(res)

        if (!orderId) {
            throw new Error("Order ID introuvable")
        }

        log(`🏆 COMMANDE CRÉÉE #${orderId}`)
        return orderId

    } catch (e: any) {

        // 🔥 debug réel PrestaShop
        const raw = e?.response?.data
        const parsed = extractPrestaShopError(raw)
        log(`❌ ERREUR ORDER: ${parsed || raw || e.message}`)
        console.error(raw || e)

        return null
    }
}

function extractPrestaShopError(xmlText: string | undefined): string | null {
    if (!xmlText || typeof xmlText !== "string") {
        return null;
    }

    try {
        const doc = new DOMParser().parseFromString(xmlText, "text/xml");
        const errors = Array.from(doc.getElementsByTagName("error"));
        if (!errors.length) {
            return null;
        }

        const messages = errors
            .map((err) => err.getElementsByTagName("message")[0]?.textContent)
            .filter((message): message is string => Boolean(message));

        return messages.length ? messages.join("; ") : null;
    } catch {
        return null;
    }
}


