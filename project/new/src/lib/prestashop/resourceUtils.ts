import type { XmlObject, XmlValue } from "../xml/xmlParser";

function asObject(value: XmlValue | undefined): XmlObject | null {
  if (!value || typeof value !== "object" || Array.isArray(value)) {
    return null;
  }
  return value;
}

function asArray(value: XmlValue | undefined): XmlValue[] {
  if (value === undefined) {
    return [];
  }
  return Array.isArray(value) ? value : [value];
}

function extractIdFromObject(input: XmlObject): number | null {
  const directId = input.id;
  if (typeof directId === "number" && Number.isFinite(directId)) {
    return directId;
  }
  if (typeof directId === "string") {
    const parsed = Number(directId);
    if (Number.isFinite(parsed)) {
      return parsed;
    }
  }

  const attributes = asObject(input._attributes);
  if (!attributes) {
    return null;
  }

  const attrId = attributes.id;
  if (typeof attrId === "number" && Number.isFinite(attrId)) {
    return attrId;
  }
  if (typeof attrId === "string") {
    const parsed = Number(attrId);
    if (Number.isFinite(parsed)) {
      return parsed;
    }
  }

  return null;
}

function collectIdsRecursively(value: XmlValue, output: Set<number>): void {
  if (value === null || typeof value !== "object") {
    return;
  }

  if (Array.isArray(value)) {
    value.forEach((entry) => collectIdsRecursively(entry, output));
    return;
  }

  const id = extractIdFromObject(value);
  if (id !== null) {
    output.add(id);
  }

  for (const nested of Object.values(value)) {
    collectIdsRecursively(nested, output);
  }
}

export function extractResourceIds(parsed: XmlObject, resourceName: string): number[] {
  const root = asObject(parsed.prestashop);
  if (!root) {
    return [];
  }

  const listNode = root[resourceName];
  const ids = new Set<number>();

  for (const entry of asArray(listNode)) {
    collectIdsRecursively(entry, ids);
  }

  return Array.from(ids);
}
