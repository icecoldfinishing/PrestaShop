export type XmlPrimitive = string | number | boolean | null;
export type XmlValue = XmlPrimitive | XmlObject | XmlValue[];

export interface XmlObject {
  [key: string]: XmlValue;
}

function normalizeText(input: string): XmlPrimitive {
  const trimmed = input.trim();

  if (trimmed.length === 0) {
    return "";
  }

  if (trimmed === "true") {
    return true;
  }

  if (trimmed === "false") {
    return false;
  }

  if (trimmed === "null") {
    return null;
  }

  const numeric = Number(trimmed);
  if (!Number.isNaN(numeric) && /^-?\d+(\.\d+)?$/.test(trimmed)) {
    return numeric;
  }

  return trimmed;
}

function parseElement(element: Element): XmlObject | XmlPrimitive {
  const children = Array.from(element.children);
  const attributes = Array.from(element.attributes);

  if (children.length === 0 && attributes.length === 0) {
    return normalizeText(element.textContent ?? "");
  }

  const output: XmlObject = {};

  if (attributes.length > 0) {
    output._attributes = attributes.reduce<XmlObject>((acc, attr) => {
      acc[attr.name] = normalizeText(attr.value);
      return acc;
    }, {});
  }

  if (children.length === 0) {
    output._text = normalizeText(element.textContent ?? "");
    return output;
  }

  for (const child of children) {
    const childValue = parseElement(child);
    const key = child.tagName;
    const existing = output[key];

    if (existing === undefined) {
      output[key] = childValue;
      continue;
    }

    if (Array.isArray(existing)) {
      existing.push(childValue);
      continue;
    }

    output[key] = [existing, childValue];
  }

  return output;
}

export function parseXmlToObject(xml: string): XmlObject {
  const parser = new DOMParser();
  const documentNode = parser.parseFromString(xml, "application/xml");
  const parserError = documentNode.querySelector("parsererror");

  if (parserError) {
    throw new Error(`Invalid XML response: ${parserError.textContent ?? "unknown parser error"}`);
  }

  const root = documentNode.documentElement;

  if (!root) {
    throw new Error("Empty XML document.");
  }

  return { [root.tagName]: parseElement(root) };
}
