import { reactive, computed } from "vue";
import axios from 'axios';
import { XMLBuilder, XMLParser } from 'fast-xml-parser';
import bcrypt from 'bcryptjs';


const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

const parser = new XMLParser({
  ignoreAttributes: false,
  attributeNamePrefix: '@_',
  parseTagValue: true,
  parseAttributeValue: true,
});

const builder = new XMLBuilder();
const DEFAULT_SHOP_ID = 1;
const DEFAULT_SHOP_GROUP_ID = 1;
const DEFAULT_CURRENCY_ID = 1;
const DEFAULT_LANG_ID = 1;
const DEFAULT_COUNTRY_ID = 1;
const DEFAULT_CARRIER_ID = 1;
const COD_MODULE = 'ps_cashondelivery';
const COD_PAYMENT = 'Paiement a la livraison';
const COD_STATE_ID = 10;

/** URL publique PrestaShop (images hors proxy). */
export const PS_PUBLIC_ORIGIN =
  import.meta.env.VITE_PRESTASHOP_PUBLIC_ORIGIN || 'http://localhost:8088';



export async function psGet(resource, id = '', queryParams = {}) {
  const resourcePath =
    id === '' || id === null || id === undefined ? resource : `${resource}/${id}`;

  const response = await axios.get(`${BASE_URL}/${resourcePath}`, {
    params: {
      ws_key: API_KEY,
      output_format: 'XML',
      ...queryParams,
    },
    headers: {
      Accept: 'application/xml',
    },
    responseType: 'text',
  });

  return parser.parse(response.data);
}

/** Lit les <error> de la réponse XML PrestaShop (API). */
export function extractPrestaShopErrorsFromXml(xmlText) {
  if (!xmlText || typeof xmlText !== 'string') return '';
  try {
    const doc = new DOMParser().parseFromString(xmlText, 'text/xml');
    const errors = doc.getElementsByTagName('error');
    const parts = [];
    for (let i = 0; i < errors.length; i++) {
      const err = errors[i];
      const code = err.getElementsByTagName('code')[0]?.textContent?.trim();
      const message = err.getElementsByTagName('message')[0]?.textContent?.trim();
      const chunk = [code && `code ${code}`, message].filter(Boolean).join(': ');
      if (chunk) parts.push(chunk);
    }
    return parts.join(' | ');
  } catch {
    return '';
  }
}

export async function psPost(resource, xmlData) {
  try {
    const response = await axios.post(`${BASE_URL}/${resource}`, xmlData, {
      params: {
        ws_key: API_KEY,
      },
      headers: {
        'Content-Type': 'application/xml',
      },
      responseType: 'text',
    });
    const body = typeof response.data === 'string' ? response.data : String(response.data ?? '');
    if (body.includes('<errors')) {
      const msg = extractPrestaShopErrorsFromXml(body) || body.slice(0, 1200);
      throw new Error(`PrestaShop (${resource}): ${msg}`);
    }
    return body;
  } catch (e) {
    if (typeof axios.isAxiosError === 'function' && axios.isAxiosError(e) && e.response?.data != null) {
      const raw = e.response.data;
      const str = typeof raw === 'string' ? raw : String(raw);
      const psMsg = extractPrestaShopErrorsFromXml(str);
      if (psMsg) {
        e.message = `${e.message || 'Erreur HTTP'} — ${psMsg}`;
      } else if (str && str.length && str.length < 2500) {
        e.message = `${e.message || 'Erreur HTTP'} — ${str}`;
      }
    }
    throw e;
  }
}

export async function psPut(resource, xmlData) {
  try {
    const response = await axios.put(`${BASE_URL}/${resource}`, xmlData, {
      params: {
        ws_key: API_KEY,
      },
      headers: {
        'Content-Type': 'application/xml',
      },
      responseType: 'text',
    });
    const body = typeof response.data === 'string' ? response.data : String(response.data ?? '');
    if (body.includes('<errors')) {
      const msg = extractPrestaShopErrorsFromXml(body) || body.slice(0, 1200);
      throw new Error(`PrestaShop (${resource}): ${msg}`);
    }
    return body;
  } catch (e) {
    if (typeof axios.isAxiosError === 'function' && axios.isAxiosError(e) && e.response?.data != null) {
      const raw = e.response.data;
      const str = typeof raw === 'string' ? raw : String(raw);
      const psMsg = extractPrestaShopErrorsFromXml(str);
      if (psMsg) {
        e.message = `${e.message || 'Erreur HTTP'} — ${psMsg}`;
      } else if (str && str.length && str.length < 2500) {
        e.message = `${e.message || 'Erreur HTTP'} — ${str}`;
      }
    }
    throw e;
  }
}

export async function psDelete(resource, id = '', queryParams = {}) {
  const resourcePath =
    id === '' || id === null || id === undefined ? resource : `${resource}/${id}`;

  return axios.delete(`${BASE_URL}/${resourcePath}`, {
    params: {
      ws_key: API_KEY,
      ...queryParams,
    },
  });
}

export async function psCount(resource) {
  try {
    const data = await psGet(resource, '', {
      display: '[id]'
    });

    const resourceData = data?.prestashop?.[resource]?.[
      resource.slice(0, -1)
    ];

    if (!resourceData) {
      return 0;
    }

    const items = Array.isArray(resourceData)
      ? resourceData
      : [resourceData];

    return items.length;

  } catch (error) {
    console.error(`Error counting ${resource}:`, error);
    return 0;
  }
}


/**
 * Retrieve all IDs for a given API resource.
 *
 * Calls GET /api/{resource}?display=[id] and parses the XML response
 * to extract an array of numeric IDs.
 *
 * @param {string} resource - The API resource name (e.g. 'customers', 'orders').
 * @param {string} [singularTag] - Optional singular tag override.
 *   If omitted the function tries resource minus trailing 's'.
 * @returns {Promise<(string|number)[]>} Array of IDs.
 */
export async function psGetAllIds(resource, singularTag) {
  try {
    const data = await psGet(resource, '', {
      display: '[id]',
    });

    const tag = singularTag || resource.replace(/s$/, '');
    const resourceData = data?.prestashop?.[resource]?.[tag];

    if (!resourceData) {
      return [];
    }

    const items = Array.isArray(resourceData)
      ? resourceData
      : [resourceData];

    return items
      .map((item) => {
        if (typeof item === 'object' && item !== null) {
          return item['@_id'] ?? item.id ?? item;
        }
        return item;
      })
      .filter((id) => id !== undefined && id !== null);
  } catch (error) {
    if (error?.response?.status === 404) {
      return [];
    }
    console.error(`Error fetching IDs for ${resource}:`, error);
    return [];
  }
}
