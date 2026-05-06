import { prestashopClient } from ".";
import { extractResourceIds } from "./resourceUtils";
import { asObject, getFieldNumber, getFieldText } from "./valueExtractors";
import type { XmlObject } from "../xml/xmlParser";

export interface ProductItem {
  id: number;
  name: string;
  price: string;
  reference: string;
  shortDescription: string;
}

export interface ProductDetail extends ProductItem {
  description: string;
  active: boolean;
}

function singularize(resourceName: string): string {
  if (resourceName.endsWith("ies")) {
    return `${resourceName.slice(0, -3)}y`;
  }
  if (resourceName.endsWith("sses")) {
    return resourceName.slice(0, -2);
  }
  if (resourceName.endsWith("s")) {
    return resourceName.slice(0, -1);
  }
  return resourceName;
}

function findDetailNode(resourceName: string, parsed: XmlObject): XmlObject | null {
  const root = asObject(parsed.prestashop);
  if (!root) {
    return null;
  }

  const singular = singularize(resourceName);
  const directSingular = asObject(root[singular]);
  if (directSingular) {
    return directSingular;
  }

  const pluralNode = asObject(root[resourceName]);
  if (!pluralNode) {
    return null;
  }

  return asObject(pluralNode[singular]) ?? pluralNode;
}

function mapProduct(product: XmlObject): ProductDetail {
  const id = getFieldNumber(product, "id") ?? 0;
  const name = getFieldText(product, "name") || `Produit #${id}`;
  const reference = getFieldText(product, "reference");
  const shortDescription = getFieldText(product, "description_short");
  const description = getFieldText(product, "description");
  const price = getFieldText(product, "price");
  const active = getFieldText(product, "active") === "1";

  return {
    id,
    name,
    reference,
    shortDescription,
    description,
    price,
    active
  };
}

export async function fetchProductList(limit = 12): Promise<ProductItem[]> {
  const list = await prestashopClient.getResource("products", {
    display: "minimum",
    limit: `0,${limit}`
  });

  const ids = extractResourceIds(list.parsed, "products").slice(0, limit);
  const details = await Promise.all(
    ids.map((id) => prestashopClient.getResourceById("products", id, { display: "full" }))
  );

  return details
    .map((entry) => findDetailNode("products", entry.parsed))
    .filter((entry): entry is XmlObject => Boolean(entry))
    .map((product) => mapProduct(product));
}

export async function fetchProductDetail(productId: number): Promise<ProductDetail> {
  const details = await prestashopClient.getResourceById("products", productId, { display: "full" });
  const product = findDetailNode("products", details.parsed);

  if (!product) {
    throw new Error(`Produit ${productId} introuvable.`);
  }

  return mapProduct(product);
}

export async function fetchModuleRecords(resourceName: string, limit = 10): Promise<XmlObject[]> {
  const list = await prestashopClient.getResource(resourceName, {
    display: "minimum",
    limit: `0,${limit}`
  });

  const ids = extractResourceIds(list.parsed, resourceName).slice(0, limit);
  const details = await Promise.all(ids.map((id) => prestashopClient.getResourceById(resourceName, id)));

  return details
    .map((entry) => findDetailNode(resourceName, entry.parsed))
    .filter((entry): entry is XmlObject => Boolean(entry));
}
