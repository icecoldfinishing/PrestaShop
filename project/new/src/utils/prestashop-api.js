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

