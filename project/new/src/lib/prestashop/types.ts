import type { XmlObject } from "../xml/xmlParser";

export interface PrestashopConfig {
  baseUrl: string;
  apiKey: string;
}

export interface ResourceQuery {
  display?: "full" | "minimum";
  limit?: string;
  sort?: string;
  filter?: Record<string, string>;
  [key: string]: string | Record<string, string> | undefined;
}

export interface PrestashopResponse<TResource extends XmlObject = XmlObject> {
  rawXml: string;
  parsed: TResource;
  resourceName: string;
}
