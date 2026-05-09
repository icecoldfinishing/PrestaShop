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
