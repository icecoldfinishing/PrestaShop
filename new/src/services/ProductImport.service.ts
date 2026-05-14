import { psGet, psPost } from "./prestashopApi.service";

export type ImportProduct = {
    nom: string;
    reference: string;
    prix_ttc: number;
    taxe: number;
    categorie: string;
    prix_achat?: number;
    date_availability_produit?: string; 
};

export type ImportLogCallback = (message: string) => void;

const DEFAULT_COUNTRY_ID = 8;
const DEFAULT_SHOP_ID = 1;
const DEFAULT_CATEGORY_ID = 2;

/**
 * Extrait l'ID d'une réponse XML de PrestaShop
 */
function getXmlId(xmlString: string): string | null {
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(xmlString, "text/xml");
    const idElement = xmlDoc.getElementsByTagName("id")[0];
    return idElement?.textContent ?? null;
}

/**
 * Gère les valeurs textuelles venant du parseur JSON/XML
 */
function getTextValue(value: unknown): string | null {
    if (value === null || value === undefined) return null;
    if (typeof value === "object" && "#text" in (value as any)) {
        return String((value as any)["#text"]);
    }
    return String(value);
}

/**
 * Force la récupération d'un ID ou lève une erreur
 */
function requireXmlId(xmlString: string, context: string): string {
    const id = getXmlId(xmlString);
    if (!id) throw new Error(`Impossible de récupérer l'ID (${context}).`);
    return id;
}

function buildSlug(value: string): string {
    return value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");
}

function calculatePrixHt(prixTtc: number, taxe: number): number {
    return prixTtc / (1 + taxe / 100);
}

/**
 * Normalise les données de l'API PrestaShop qui peut retourner 
 * un objet seul ou un tableau selon le nombre de résultats.
 */
function normalizeApiCollection<T>(value: T | T[] | undefined | null): T[] {
    if (!value) return [];
    return Array.isArray(value) ? value : [value];
}

function formatAvailabilityDate(input?: string): string {
    if (!input) {
        return new Date().toISOString().slice(0, 19).replace('T', ' ');
    }

    const parts = input.split('/');
    if (parts.length === 3) {
        const [day, month, year] = parts;
        return `${year}-${month}-${day} 00:00:00`;
    }

    return `${input} 00:00:00`;
}
async function getOrCreateCategoryId(
    categoryName: string
): Promise<string> {

    const existing = await psGet('categories', '', {
        display: 'full',
        'filter[name]': `[${categoryName}]`,
    });

    const cats = normalizeApiCollection(
        (existing as any)?.prestashop?.categories?.category
    );

    if (cats.length > 0) {
        const existingId = getTextValue(cats[0]?.id);

        if (existingId) {
            return existingId;
        }
    }

    const slug = buildSlug(categoryName);

    const xml = `
<prestashop>
    <category>
        <id_parent>2</id_parent>
        <active>1</active>

        <name>
            <language id="1"><![CDATA[${categoryName}]]></language>
        </name>

        <link_rewrite>
            <language id="1"><![CDATA[${slug}]]></language>
        </link_rewrite>
    </category>
</prestashop>
`;

    return requireXmlId(
        await psPost('categories', xml),
        'création catégorie'
    );
}

/**
 * SOLUTION ANTI-ECRASEMENT :
 * Crée un groupe de taxe unique par référence produit.
 */
async function getSafeTaxGroupId(product: ImportProduct): Promise<string> {
    const groupName = `TVA_${product.reference}`;
    const rateStr = product.taxe.toFixed(3);

    // 1. Vérification si le groupe existe déjà (Isolation par Nom)
    const groupList = await psGet("tax_rule_groups", "", {
        display: "full",
        "filter[name]": `[${groupName}]`, // Recherche exacte
    });

    const groups = normalizeApiCollection(
        (groupList as any)?.prestashop?.tax_rule_groups?.tax_rule_group
    );
    
    let existingId = null;
    if (groups.length > 0) {
        existingId = getTextValue(groups[0]?.id);
    }

    if (existingId) {
        return existingId; // On sort immédiatement : AUCUNE modification sur l'existant
    }

    // 2. Recherche ou création du taux de taxe (Tax)
    const taxList = await psGet("taxes", "", {
        display: "full",
        "filter[rate]": rateStr,
    });
    const taxes = normalizeApiCollection(
        (taxList as any)?.prestashop?.taxes?.tax
    );
    let taxId = taxes.length > 0 ? getTextValue(taxes[0]?.id) : null;

    if (!taxId) {
        const taxXml = `<prestashop><tax>
            <rate>${product.taxe}</rate>
            <active>1</active>
            <name><language id="1"><![CDATA[Taux ${product.taxe}%]]></language></name>
        </tax></prestashop>`;
        taxId = requireXmlId(await psPost("taxes", taxXml), "création taxe");
    }

    // 3. Création du groupe de règle (Tax Rule Group)
    const groupXml = `<prestashop><tax_rule_group>
        <name><![CDATA[${groupName}]]></name>
        <active>1</active>
    </tax_rule_group></prestashop>`;
    const taxGroupId = requireXmlId(
        await psPost("tax_rule_groups", groupXml),
        "création groupe"
    );

    // 4. Création de la règle de liaison pays (Tax Rule)
    const ruleXml = `<prestashop><tax_rule>
        <id_tax_rules_group>${taxGroupId}</id_tax_rules_group>
        <id_country>${DEFAULT_COUNTRY_ID}</id_country>
        <id_state>0</id_state>
        <id_tax>${taxId}</id_tax>
        <behavior>0</behavior>
    </tax_rule></prestashop>`;
    await psPost("tax_rules", ruleXml);

    return taxGroupId;
}

/**
 * Construit le XML du produit
 */
function buildProductXml(product: ImportProduct, taxGroupId: string, categoryId: string ): string {
    const prixHt = calculatePrixHt(product.prix_ttc, product.taxe);
    const slug = buildSlug(product.nom);
    const wholesale = product.prix_achat ? product.prix_achat.toFixed(6) : "0.000000";

    const availabilityDate = formatAvailabilityDate(product.date_availability_produit);

    return `<prestashop>
    <product>
        <id_shop_default>${DEFAULT_SHOP_ID}</id_shop_default>
        <id_category_default>${categoryId}</id_category_default>
        <id_tax_rules_group>${taxGroupId}</id_tax_rules_group>
        <price>${prixHt.toFixed(6)}</price>
        <wholesale_price>${wholesale}</wholesale_price>
        <active>1</active>
        <state>1</state>
        <reference>${product.reference}</reference>
        <name><language id="1"><![CDATA[${product.nom}]]></language></name>
        <link_rewrite><language id="1"><![CDATA[${slug}]]></language></link_rewrite>

        <!-- ✅ AJOUT IMPORTANT -->
        <available_date>${availabilityDate}</available_date>

        <associations>
            <categories>
                <category><id>${categoryId}</id></category>
            </categories>
        </associations>
    </product>
</prestashop>`;
}

function formatError(error: unknown): string {
    if (error instanceof Error) return error.message;
    return String(error);
}

/**
 * Fonction principale d'importation
 */
export async function runImport(
    products: ImportProduct[],
    logCallback: ImportLogCallback
): Promise<void> {
    const log = typeof logCallback === "function" ? logCallback : () => { };

    if (!Array.isArray(products)) {
        log("ERREUR : La liste de produits est invalide.");
        return;
    }

    log(`Démarrage de l'import de ${products.length} produits...`);

    for (const product of products) {
        try {
            // Sécurisation de la taxe
            const taxGroupId = await getSafeTaxGroupId(product);
            
            // Préparation du XML
            const categoryId = await getOrCreateCategoryId(product.categorie);
            const productXml = buildProductXml(product, taxGroupId, categoryId);
            
            // Envoi à PrestaShop
            await psPost("products", productXml);

            log(`✅ [${product.reference}] Succès (TaxGroup: ${taxGroupId}, Category: ${categoryId})`);
        } catch (error) {
            log(`❌ [${product.reference}] Erreur : ${formatError(error)}`);
        }
    }

    log("🏁 Importation terminée. Sécurité fiscale maintenue.");
}