import { XMLBuilder } from 'fast-xml-parser';
import type { CsvRow, ResourceConfig } from '../types/import.types';

const builder = new XMLBuilder({
  ignoreAttributes: false,
  attributeNamePrefix: '@_',
  suppressEmptyNode: true
});

export function buildXML(rows: CsvRow[], resourceConfig: ResourceConfig): string {
  const items = rows.map((row) => buildItem(row, resourceConfig));
  const root: Record<string, unknown> = {};

  if (items.length === 1) {
    root[resourceConfig.xmlRootTag] = {
      [resourceConfig.xmlItemTag]: items[0]
    };
  } else {
    root[resourceConfig.xmlRootTag] = {
      [resourceConfig.xmlItemTag]: items
    };
  }

  return builder.build(root);
}

function buildItem(row: CsvRow, resourceConfig: ResourceConfig): Record<string, unknown> {
  const item: Record<string, unknown> = {};
  const listSeparator = resourceConfig.listSeparator || '|';

  Object.entries(resourceConfig.mapping).forEach(([header, path]) => {
    const normalizedPath = normalizePath(path, resourceConfig.xmlItemTag);
    const rawValue = row[header];
    if (rawValue === undefined || rawValue === null || rawValue === '') {
      return;
    }

    if (shouldUseLanguageWrapper(normalizedPath, resourceConfig)) {
      setLanguageValue(item, normalizedPath, String(rawValue), resourceConfig.defaultLanguageId);
      return;
    }

    const values = splitListValues(String(rawValue), listSeparator, normalizedPath);
    if (values.length > 1 && pathHasArray(normalizedPath)) {
      values.forEach((value) => {
        setPathValue(item, normalizedPath, value, true);
      });
      return;
    }

    setPathValue(item, normalizedPath, String(rawValue), false);
  });

  return item;
}

function normalizePath(path: string, xmlItemTag: string): string {
  const trimmed = path.trim().replace(/^\//, '');
  const prefix = `${xmlItemTag}/`;
  if (trimmed.startsWith(prefix)) {
    return trimmed.slice(prefix.length);
  }
  return trimmed;
}

function shouldUseLanguageWrapper(path: string, config: ResourceConfig): boolean {
  if (!config.languageFields || config.languageFields.length === 0) {
    return false;
  }

  if (pathHasArray(path) || path.includes('@')) {
    return false;
  }

  return config.languageFields.includes(path);
}

function setLanguageValue(
  target: Record<string, unknown>,
  path: string,
  value: string,
  languageId?: number
): void {
  const segments = parsePath(path);
  let cursor: Record<string, unknown> = target;

  for (let i = 0; i < segments.length; i += 1) {
    const segment = segments[i];
    const name = segment.name as string;

    if (!name || segment.attrName || segment.isAttributeOnly) {
      setPathValue(target, path, value, false);
      return;
    }

    if (segment.isArray) {
      setPathValue(target, path, value, false);
      return;
    }

    if (!cursor[name]) {
      cursor[name] = {};
    }

    if (i === segments.length - 1) {
      const languageNode: Record<string, unknown> = {
        '@_id': languageId ?? 1,
        '#text': value
      };

      const current = cursor[name] as Record<string, unknown>;
      if (current.language) {
        if (Array.isArray(current.language)) {
          current.language.push(languageNode);
        } else {
          current.language = [current.language, languageNode];
        }
      } else {
        current.language = languageNode;
      }
      return;
    }

    cursor = cursor[name] as Record<string, unknown>;
  }
}

function splitListValues(value: string, separator: string, path: string): string[] {
  if (!value.includes(separator)) {
    return [value];
  }

  if (!pathHasArray(path) && !path.endsWith('/@id')) {
    return [value];
  }

  return value
    .split(separator)
    .map((part) => part.trim())
    .filter(Boolean);
}

function pathHasArray(path: string): boolean {
  return path.includes('[]');
}

type Segment = {
  name?: string;
  isArray?: boolean;
  attrName?: string;
  isAttributeOnly?: boolean;
};

function parsePath(path: string): Segment[] {
  return path
    .split('/')
    .filter(Boolean)
    .map((segment) => {
      let raw = segment.trim();
      if (raw.startsWith('@')) {
        return { attrName: raw.slice(1), isAttributeOnly: true };
      }

      const isArray = raw.endsWith('[]');
      if (isArray) {
        raw = raw.slice(0, -2);
      }

      if (raw.includes('@')) {
        const [name, attrName] = raw.split('@');
        return { name, attrName, isArray };
      }

      return { name: raw, isArray };
    });
}

function setPathValue(
  target: Record<string, unknown>,
  path: string,
  value: string,
  forceArrayItem: boolean
): void {
  const segments = parsePath(path);
  let cursor: Record<string, unknown> = target;
  let arrayConsumed = false;

  for (let i = 0; i < segments.length; i += 1) {
    const segment = segments[i];
    const isLast = i === segments.length - 1;

    if (segment.isAttributeOnly && segment.attrName) {
      setAttribute(cursor, segment.attrName, value);
      return;
    }

    const name = segment.name as string;

    if (segment.isArray) {
      const list = (cursor[name] as Array<Record<string, unknown>>) || [];
      cursor[name] = list;

      const useNewItem = forceArrayItem || !arrayConsumed;
      const node = useNewItem ? {} : list[0] || {};

      if (useNewItem) {
        list.push(node);
      } else if (list.length === 0) {
        list.push(node);
      }

      arrayConsumed = true;

      if (segment.attrName) {
        setAttribute(node, segment.attrName, value);
        return;
      }

      if (isLast) {
        setTextValue(node, value);
        return;
      }

      cursor = node;
      continue;
    }

    if (!cursor[name]) {
      cursor[name] = {};
    }

    const node = cursor[name] as Record<string, unknown>;

    if (segment.attrName) {
      setAttribute(node, segment.attrName, value);
      return;
    }

    if (isLast) {
      if (typeof node === 'object' && Object.keys(node).length > 0) {
        setTextValue(node, value);
      } else {
        cursor[name] = value;
      }
      return;
    }

    cursor = node;
  }
}

function setAttribute(target: Record<string, unknown>, attrName: string, value: string): void {
  if (attrName === 'value') {
    setTextValue(target, value);
    return;
  }

  target[`@_${attrName}`] = value;
}

function setTextValue(target: Record<string, unknown>, value: string): void {
  target['#text'] = value;
}
