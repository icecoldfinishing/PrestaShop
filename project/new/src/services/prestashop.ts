import axios from "axios";

const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY ?? "";
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL ?? "/api";

function buildParams(params?: Record<string, unknown>) {
  return {
    ws_key: API_KEY,
    ...params
  };
}

const client = axios.create({
  baseURL: BASE_URL
});

const prestashop = {
  get(url: string, params?: Record<string, unknown>) {
    return client.get<string>(url, {
      params: buildParams({ output_format: "XML", ...params }),
      headers: {
        Accept: "application/xml"
      },
      responseType: "text"
    });
  },
  post(url: string, data: string) {
    return client.post<string>(url, data, {
      params: buildParams(),
      headers: {
        "Content-Type": "application/xml"
      },
      responseType: "text"
    });
  },
  put(url: string, data: string) {
    return client.put<string>(url, data, {
      params: buildParams(),
      headers: {
        "Content-Type": "application/xml"
      },
      responseType: "text"
    });
  }
};

export default prestashop;
