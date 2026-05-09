import axios from 'axios';
import { XMLBuilder, XMLParser } from 'fast-xml-parser';


const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

const parser = new XMLParser({
  ignoreAttributes: false,
  attributeNamePrefix: '@_',
  parseTagValue: true,
  parseAttributeValue: true,
});

const builder = new XMLBuilder();

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
  });

  return parser.parse(response.data);
}

export async function psPost(resource, xmlData) {
    const response = await axios.post(`${BASE_URL}/${resource}`, xmlData, {
        params: {
            ws_key: API_KEY,
        },
        headers: {
            'Content-Type': 'application/xml',
        },
    });
    return response.data;
}

export async function psPut(resource, xmlData) {
    const response = await axios.put(`${BASE_URL}/${resource}`, xmlData, {
        params: {
            ws_key: API_KEY,
        },
        headers: {
            'Content-Type': 'application/xml',
        },
    });
    return response.data;
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

export function getXmlText(value) {
  if (value === null || value === undefined) {
    return '';
  }

  if (typeof value === 'object' && '#text' in value) {
    return String(value['#text']).trim();
  }

  return String(value).trim();
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
