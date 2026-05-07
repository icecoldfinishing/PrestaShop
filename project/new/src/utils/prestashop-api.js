import axios from 'axios';
import { XMLBuilder, XMLParser } from 'fast-xml-parser';

const API_KEY = 'EWWCJGTGEU8ZQFT5YTGEGQNTZLMJG688';
const BASE_URL = '/api';

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

export async function psPost(resource, xmlData, queryParams = {}) {
  const xml = builder.build(xmlData);

  return axios.post(`${BASE_URL}/${resource}`, xml, {
    params: {
      ws_key: API_KEY,
      ...queryParams,
    },
    headers: {
      'Content-Type': 'application/xml',
    },
  });
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