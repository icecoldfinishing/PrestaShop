import { PrestashopClient } from "./client";

const baseUrl = import.meta.env.VITE_PRESTASHOP_API_BASE_URL ?? "/prestashop/api";
const apiKey = import.meta.env.VITE_PRESTASHOP_API_KEY ?? "";

export const prestashopClient = new PrestashopClient({
  baseUrl,
  apiKey
});
