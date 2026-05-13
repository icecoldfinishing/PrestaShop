import axios from "axios";
import { XMLParser } from "fast-xml-parser";
import type { PrestashopApiConfig, PrestashopQueryParams } from "../types/prestashopApi.types";

const prestashopApiConfig: PrestashopApiConfig = {
  apiKey: import.meta.env.VITE_PRESTASHOP_API_KEY ?? "",
  baseUrl: import.meta.env.VITE_PRESTASHOP_BASE_URL ?? "/api",
};

const parser = new XMLParser({
  ignoreAttributes: false,
  attributeNamePrefix: "@_",
  parseTagValue: true,
  parseAttributeValue: true,
});

function getResourcePath(resource: string, id: string | number | null | undefined): string {
  return id === "" || id === null || id === undefined ? resource : `${resource}/${id}`;
}

export async function psGet(
  resource: string,
  id: string | number | null | undefined = "",
  queryParams: PrestashopQueryParams = {}
): Promise<unknown> {
  const resourcePath = getResourcePath(resource, id);

  const response = await axios.get<string>(`${prestashopApiConfig.baseUrl}/${resourcePath}`, {
    params: {
      ws_key: prestashopApiConfig.apiKey,
      output_format: "XML",
      ...queryParams,
    },
    headers: {
      Accept: "application/xml",
    },
  });

  return parser.parse(response.data);
}

export async function psPost(resource: string, xmlData: string): Promise<string> {
  const response = await axios.post<string>(
    `${prestashopApiConfig.baseUrl}/${resource}`,
    xmlData,
    {
      params: {
        ws_key: prestashopApiConfig.apiKey,
      },
      headers: {
        "Content-Type": "application/xml",
      },
    }
  );

  return response.data;
}

// ... (conserve tout ton code actuel en haut)

// Ajoute cette fonction pour permettre la mise à jour (Stock)
export async function psPut(resource: string, xmlData: string): Promise<string> {
  const response = await axios.put<string>(
    `${prestashopApiConfig.baseUrl}/${resource}`,
    xmlData,
    {
      params: {
        ws_key: prestashopApiConfig.apiKey,
      },
      headers: {
        "Content-Type": "application/xml",
      },
    }
  );

  return response.data;
}

// Ajoute ces helpers indispensables pour le parsing
export const getXmlId = (xml: string) => {
    if (typeof xml !== 'string') return null;
    const d = new DOMParser().parseFromString(xml, "text/xml");
    return d.getElementsByTagName('id')[0]?.textContent || null;
};

export const getXmlText = (value: any) => {
    if (!value) return '';
    // PrestaShop XML parser peut retourner un objet {"#text": "valeur"} ou juste "valeur"
    return typeof value === 'object' ? value['#text'] ?? String(value) : String(value);
};

export const sleep = (ms: number) => new Promise(res => setTimeout(res, ms));