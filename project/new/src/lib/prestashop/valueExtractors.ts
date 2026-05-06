import type { XmlObject, XmlValue } from "../xml/xmlParser";

export function asObject(value: XmlValue | undefined): XmlObject | null {
  if (!value || typeof value !== "object" || Array.isArray(value)) {
    return null;
  }
  return value;
}

export function asArray(value: XmlValue | undefined): XmlValue[] {
  if (value === undefined) {
    return [];
  }
  return Array.isArray(value) ? value : [value];
}

export function getText(value: XmlValue | undefined): string {
  if (value === undefined || value === null) {
    return "";
  }
  if (typeof value === "string" || typeof value === "number" || typeof value === "boolean") {
    return String(value);
  }
  if (Array.isArray(value)) {
    for (const entry of value) {
      const text = getText(entry).trim();
      if (text) {
        return text;
      }
    }
    return "";
  }

  const asObj = asObject(value);
  if (!asObj) {
    return "";
  }

  const directText = getText(asObj._text);
  if (directText.trim()) {
    return directText;
  }

  if (asObj.language !== undefined) {
    return getText(asObj.language);
  }

  for (const nested of Object.values(asObj)) {
    const text = getText(nested).trim();
    if (text) {
      return text;
    }
  }

  return "";
}

export function getNumber(value: XmlValue | undefined): number | null {
  const text = getText(value).trim();
  if (!text) {
    return null;
  }
  const parsed = Number(text);
  return Number.isFinite(parsed) ? parsed : null;
}

export function getFieldText(source: XmlObject, key: string): string {
  return getText(source[key]).trim();
}

export function getFieldNumber(source: XmlObject, key: string): number | null {
  return getNumber(source[key]);
}
