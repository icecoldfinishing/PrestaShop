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

/**
 * Authentifie un client par email et vérifie le mot de passe (hash).
 * 
 * @param {string} email 
 * @param {string} password 
 * @returns {Promise<Object>} Le client authentifié
 * @throws {Error} Si l'authentification échoue
 */
export async function psLoginCustomer(email, password) {
  if (!email || !password) {
    throw new Error('Email et mot de passe requis.');
  }

  const data = await psGet('customers', '', {
    'filter[email]': `[${email}]`,
    display: 'full',
  });

  const customerData = data?.prestashop?.customers?.customer;
  const customer = Array.isArray(customerData) ? customerData[0] : customerData;

  if (!customer) {
    throw new Error('Identifiants incorrects (Email non trouvé).');
  }

  const dbPasswd = getXmlText(customer.passwd);

  // PrestaShop 1.7+ utilise bcrypt. On compare le texte brut avec le hash.
  let isMatch = false;
  try {
    if (dbPasswd.startsWith('$2y$') || dbPasswd.startsWith('$2a$')) {
      isMatch = bcrypt.compareSync(password, dbPasswd);
    } else {
      // Fallback pour les anciens hash ou texte brut (utile en dev)
      isMatch = (password === dbPasswd);
    }
  } catch (error) {
    console.error('Erreur lors de la vérification du mot de passe:', error);
    isMatch = (password === dbPasswd);
  }

  if (isMatch) {
    return {
      id: customer.id,
      email: getXmlText(customer.email),
      firstname: getXmlText(customer.firstname),
      lastname: getXmlText(customer.lastname),
    };
  } else {
    throw new Error('Mot de passe incorrect.');
  }
}

/**
 * Met a jour l'etat d'une commande via order_history.
 *
 * @param {string|number} orderId
 * @param {string|number} stateId
 * @returns {Promise<string>}
 */
export async function psUpdateOrderState(orderId, stateId) {
  if (!orderId || !stateId) {
    throw new Error("Order ID et state ID requis.");
  }

  const xmlData = builder.build({
    prestashop: {
      order_history: {
        id_order: String(orderId),
        id_order_state: String(stateId),
      },
    },
  });

  return psPost("order_histories", xmlData);
}
