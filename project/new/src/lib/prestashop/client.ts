import { parseXmlToObject, type XmlObject } from "../xml/xmlParser";
import type { PrestashopConfig, PrestashopResponse, ResourceQuery } from "./types";
import { extractResourceIds } from "./resourceUtils";

function toQueryString(query?: ResourceQuery): string {
  if (!query) {
    return "";
  }

  const params = new URLSearchParams();

  for (const [key, value] of Object.entries(query)) {
    if (value === undefined) {
      continue;
    }

    if (typeof value === "object") {
      for (const [filterKey, filterValue] of Object.entries(value)) {
        params.append(`${key}[${filterKey}]`, filterValue);
      }
      continue;
    }

    params.append(key, value);
  }

  const result = params.toString();
  return result ? `?${result}` : "";
}

function normalizeBaseUrl(url: string): string {
  const trimmed = url.trim();
  if (!trimmed) {
    throw new Error("PrestaShop base URL is empty.");
  }
  return trimmed.replace(/\/+$/, "");
}

export class PrestashopClient {
  private readonly baseUrl: string;
  private readonly apiKey: string;

  constructor(config: PrestashopConfig) {
    this.baseUrl = normalizeBaseUrl(config.baseUrl);
    this.apiKey = config.apiKey.trim();

    if (!this.apiKey) {
      throw new Error("PrestaShop API key is missing.");
    }
  }

  async getResource<TResource extends XmlObject = XmlObject>(
    resourceName: string,
    query?: ResourceQuery
  ): Promise<PrestashopResponse<TResource>> {
    const resource = resourceName.trim();

    if (!resource) {
      throw new Error("Resource name cannot be empty.");
    }

    const queryString = toQueryString(query);
    const response = await fetch(`${this.baseUrl}/${resource}${queryString}`, {
      method: "GET",
      headers: {
        Authorization: `Basic ${btoa(`${this.apiKey}:`)}`,
        Accept: "application/xml",
        "Output-Format": "XML"
      }
    });

    const rawXml = await response.text();

    if (!response.ok) {
      throw new Error(`PrestaShop API error (${response.status}): ${rawXml}`);
    }

    const parsed = parseXmlToObject(rawXml) as TResource;
    return {
      rawXml,
      parsed,
      resourceName: resource
    };
  }

  async getResourceById<TResource extends XmlObject = XmlObject>(
    resourceName: string,
    id: number | string,
    query?: ResourceQuery
  ): Promise<PrestashopResponse<TResource>> {
    const resource = resourceName.trim();
    const normalizedId = String(id).trim();

    if (!resource) {
      throw new Error("Resource name cannot be empty.");
    }
    if (!normalizedId) {
      throw new Error("Resource id cannot be empty.");
    }

    const queryString = toQueryString(query);
    const response = await fetch(`${this.baseUrl}/${resource}/${normalizedId}${queryString}`, {
      method: "GET",
      headers: {
        Authorization: `Basic ${btoa(`${this.apiKey}:`)}`,
        Accept: "application/xml",
        "Output-Format": "XML"
      }
    });

    const rawXml = await response.text();
    if (!response.ok) {
      throw new Error(`PrestaShop API error (${response.status}): ${rawXml}`);
    }

    return {
      rawXml,
      parsed: parseXmlToObject(rawXml) as TResource,
      resourceName: resource
    };
  }

  async getResourceListWithDetails<TResource extends XmlObject = XmlObject>(
    resourceName: string,
    options?: { listLimit?: number; detailLimit?: number }
  ): Promise<Array<PrestashopResponse<TResource>>> {
    const listLimit = options?.listLimit ?? 10;
    const detailLimit = options?.detailLimit ?? 10;

    const listResponse = await this.getResource(resourceName, {
      display: "minimum",
      limit: `0,${listLimit}`
    });

    const ids = extractResourceIds(listResponse.parsed, resourceName).slice(0, detailLimit);

    const details = await Promise.all(ids.map((id) => this.getResourceById<TResource>(resourceName, id)));
    return details;
  }
}
