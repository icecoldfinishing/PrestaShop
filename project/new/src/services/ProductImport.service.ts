import { psGet, psPost } from "./prestashopApi.service";

export type ImportProduct = {
    nom: string;
    reference: string;
    prix_ttc: number;
    taxe: number;
};

export type ImportLogCallback = (message: string) => void;

const DEFAULT_COUNTRY_ID = 8;
const DEFAULT_SHOP_ID = 1;
const DEFAULT_CATEGORY_ID = 2;

function getXmlId(xmlString: string): string | null {
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(xmlString, "text/xml");
    const idElement = xmlDoc.getElementsByTagName("id")[0];
    return idElement?.textContent ?? null;
}

function getTextValue(value: unknown): string | null {
    if (value === null || value === undefined) {
        return null;
    }

    if (typeof value === "object" && value !== null && "#text" in value) {
        return String((value as Record<string, unknown>)["#text"]);
    }

    return String(value);
}

function requireXmlId(xmlString: string, context: string): string {
    const id = getXmlId(xmlString);
    if (!id) {
        throw new Error(`Impossible de recuperer l'id (${context}).`);
    }
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

function normalizeApiCollection<T>(value: T | T[] | undefined | null): T[] {
    if (!value) {
        return [];
    }
    return Array.isArray(value) ? value : [value];
}

async function getSafeTaxGroupId(product: ImportProduct): Promise<string> {
    const groupName = `TVA_${product.reference}`;
    const rateStr = product.taxe.toFixed(3);

    const groupList = await psGet("tax_rule_groups", "", {
        display: "full",
        "filter[name]": groupName,
    });

    const groups = normalizeApiCollection(
        (groupList as any)?.prestashop?.tax_rule_groups?.tax_rule_group
    );
    const existingId = getTextValue(groups[0]?.id);

    if (existingId) {
        return existingId;
    }

    const taxList = await psGet("taxes", "", {
        display: "full",
        "filter[rate]": rateStr,
    });
    const taxes = normalizeApiCollection(
        (taxList as any)?.prestashop?.taxes?.tax
    );
    let taxId = getTextValue(taxes[0]?.id);

    if (!taxId) {
        const taxXml = `<prestashop><tax>
  <rate>${product.taxe}</rate>
  <active>1</active>
  <name><language id="1"><![CDATA[Taux ${product.taxe}%]]></language></name>
</tax></prestashop>`;
        taxId = requireXmlId(await psPost("taxes", taxXml), "taxe");
    }

    const groupXml = `<prestashop><tax_rule_group>
  <name><![CDATA[${groupName}]]></name>
  <active>1</active>
</tax_rule_group></prestashop>`;
    const taxGroupId = requireXmlId(
        await psPost("tax_rule_groups", groupXml),
        "groupe"
    );

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

function buildProductXml(product: ImportProduct, taxGroupId: string): string {
    const prixHt = calculatePrixHt(product.prix_ttc, product.taxe);
    const slug = buildSlug(product.nom);

    return `<prestashop>
    <product>
        <id_shop_default>${DEFAULT_SHOP_ID}</id_shop_default>
        <id_category_default>${DEFAULT_CATEGORY_ID}</id_category_default>
        <id_tax_rules_group>${taxGroupId}</id_tax_rules_group>
        <price>${prixHt.toFixed(6)}</price>
        <active>1</active>
        <state>1</state>
        <reference>${product.reference}</reference>
        <name><language id="1"><![CDATA[${product.nom}]]></language></name>
        <link_rewrite><language id="1"><![CDATA[${slug}]]></language></link_rewrite>
    </product>
</prestashop>`;
}

function formatError(error: unknown): string {
    if (error instanceof Error) {
        return error.message;
    }
    if (typeof error === "string") {
        return error;
    }
    return "Erreur inconnue";
}

export async function runImport(
    products: ImportProduct[],
    logCallback: ImportLogCallback
): Promise<void> {
    const log = typeof logCallback === "function" ? logCallback : () => { };
    const items = Array.isArray(products) ? products : [];

    if (!Array.isArray(products)) {
        log("ERREUR Liste de produits invalide.");
    }

    for (const product of items) {
        try {
            const taxGroupId = await getSafeTaxGroupId(product);
            const productXml = buildProductXml(product, taxGroupId);
            await psPost("products", productXml);

            log(`Produit ${product.reference} traite (Groupe Taxe : ${taxGroupId})`);
        } catch (error) {
            log(`Erreur sur ${product.reference} : ${formatError(error)}`);
        }
    }

    log("Fin de l'import. Les anciens produits n'ont pas ete touches.");
}
