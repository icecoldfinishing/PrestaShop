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

export async function psLoginAdmin(email, password) {
  if (!email || !password) {
    throw new Error('Email et mot de passe requis.');
  }
  const data = await psGet('employees', '', {
    'filter[email]': `[${email}]`,
    display: 'full',
  });
  const employeeData = data?.prestashop?.employees?.employee;
  const employee = Array.isArray(employeeData) ? employeeData[0] : employeeData;

  if (!employee) {
    throw new Error('Identifiants incorrects (Email non trouvé).');
  }

  const dbPasswd = getXmlText(employee.passwd);
  let isMatch = false;
  try {
    if (dbPasswd.startsWith('$2y$') || dbPasswd.startsWith('$2a$')) { 
      isMatch = bcrypt.compareSync(password, dbPasswd);
    } else {  
      isMatch = (password === dbPasswd);
    }
  } catch (error) {
    console.error('Erreur lors de la vérification du mot de passe:', error);
    isMatch = (password === dbPasswd);
  }

  if (isMatch) {
    return {
      id: employee.id,
      email: getXmlText(employee.email),
      firstname: getXmlText(employee.firstname),
      lastname: getXmlText(employee.lastname),
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

export function getXmlText(value) {
  if (value === null || value === undefined) return '';
  if (typeof value === 'object' && '#text' in value) {
    return String(value['#text']).trim();
  }
  return String(value).trim();
}

// Nettoie les IDs (gère les objets { #text: id })
export const cleanId = (idField) => {
  if (!idField) return '';
  if (typeof idField === 'object') {
    return String(idField['#text'] || idField['@_id'] || '').trim();
  }
  return String(idField).trim();
};

// Extrait le texte multilingue
export const extractText = (field) => {
  if (!field) return '';
  const target = field.language ? field.language : field;
  return getXmlText(target);
};

/**
 * ==========================================
 * ADVANCED PRODUCT DATA FUNCTIONS
 * ==========================================
 */

/**
 * Récupère les caractéristiques et variantes d'un produit avec noms complets
 */
export async function psGetProductFullDetails(productId) {
  try {
    // 1. Dictionnaires (Idéalement, à mettre en cache hors de la fonction si appelée souvent)
    const [allFeaturesData, allGroupsData] = await Promise.all([
      psGet('product_features', null, { display: 'full' }),
      psGet('product_options', null, { display: 'full' })
    ]);

    const featureMap = {};
    [].concat(allFeaturesData?.prestashop?.product_features?.product_feature || []).forEach(f => {
      featureMap[cleanId(f.id)] = extractText(f.name);
    });

    const groupMap = {};
    [].concat(allGroupsData?.prestashop?.product_options?.product_option || []).forEach(g => {
      groupMap[cleanId(g.id)] = extractText(g.name);
    });

    // 2. Le Produit (Appel principal)
    const productResponse = await psGet('products', productId);
    const p = productResponse?.prestashop?.product;
    if (!p) throw new Error("Produit non trouvé");

    // --- Features (Specs) ---
    const pFeatures = [].concat(p.associations?.product_features?.product_feature || []);
    const features = await Promise.all(pFeatures.map(async (pf) => {
      const fId = cleanId(pf.id_feature);
      const valData = await psGet('product_feature_values', cleanId(pf.id_feature_value));
      return `${featureMap[fId] || 'Spec'}: ${extractText(valData?.prestashop?.product_feature_value?.value)}`;
    }));

    // --- Variants (Combinations) ---
    const pCombos = [].concat(p.associations?.combinations?.combination || []);
    const variants = [];
    for (const combo of pCombos) {
      const comboDetail = await psGet('combinations', cleanId(combo.id));
      const attrValues = [].concat(comboDetail?.prestashop?.combination?.associations?.product_option_values?.product_option_value || []);
      const labelParts = await Promise.all(attrValues.map(async (av) => {
        const attrValData = await psGet('product_option_values', cleanId(av.id));
        const avData = attrValData?.prestashop?.product_option_value;
        return `${groupMap[cleanId(avData?.id_attribute_group)] || 'Attr'}: ${extractText(avData?.name)}`;
      }));
      variants.push(labelParts.join(' / '));
    }

    // ON RETOURNE TOUT : Le produit brut + les labels calculés
    return {
      raw: p,
      features,
      variants
    };

  } catch (error) {
    console.error("Error in psGetProductFullDetails:", error);
    throw error;
  }
}