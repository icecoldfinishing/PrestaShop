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
const CART_STORAGE_KEY = 'fo_cart';
const DEFAULT_SHOP_ID = 1;
const DEFAULT_SHOP_GROUP_ID = 1;
const DEFAULT_CURRENCY_ID = 1;
const DEFAULT_LANG_ID = 1;
const DEFAULT_COUNTRY_ID = 1;
const DEFAULT_CARRIER_ID = 1;
const COD_MODULE = 'ps_cashondelivery';
const COD_PAYMENT = 'Paiement a la livraison';
const COD_STATE_ID = 10;

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

export function getXmlId(xml) {
  if (!xml || typeof xml !== 'string') return '';
  const doc = new DOMParser().parseFromString(xml, 'text/xml');
  return doc.getElementsByTagName('id')[0]?.textContent || '';
}

export async function psGetCustomerSecureKey(customerId) {
  if (!customerId) return '';
  const data = await psGet('customers', customerId, { display: '[id,secure_key]' });
  return getXmlText(data?.prestashop?.customer?.secure_key) || '';
}

export async function psEnsureCustomerAddress(customer, defaults = {}) {
  const customerId = customer?.id;
  if (!customerId) return null;

  const existing = await psGet('addresses', '', {
    'filter[id_customer]': `[${customerId}]`,
    display: '[id]'
  });

  const addr = existing?.prestashop?.addresses?.address;
  const firstId = Array.isArray(addr) ? getXmlText(addr[0]?.id) : getXmlText(addr?.id);
  if (firstId) return firstId;

  const address1 = defaults.address1 || 'Adresse par defaut';
  const city = defaults.city || 'Antananarivo';
  const postcode = defaults.postcode || '101';
  const phone = defaults.phone || '0000000000';
  const alias = defaults.alias || 'Adresse';
  const lastname = defaults.lastname || customer?.lastname || 'Client';
  const firstname = defaults.firstname || customer?.firstname || 'Client';

  const xml = `<prestashop><address>
    <id_customer>${customerId}</id_customer>
    <id_country>${DEFAULT_COUNTRY_ID}</id_country>
    <alias>${alias}</alias>
    <lastname>${lastname}</lastname>
    <firstname>${firstname}</firstname>
    <address1>${address1}</address1>
    <city>${city}</city>
    <postcode>${postcode}</postcode>
    <phone>${phone}</phone>
  </address></prestashop>`;

  const response = await psPost('addresses', xml);
  return getXmlId(response);
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
    // Dans psGetProductFullDetails, remplace la partie --- Variants --- par ceci :
    const pCombos = [].concat(p.associations?.combinations?.combination || []);
    const groupedVariants = {}; // Nouvel objet de regroupement

    for (const combo of pCombos) {
      const comboDetail = await psGet('combinations', cleanId(combo.id));
      const attrValues = [].concat(comboDetail?.prestashop?.combination?.associations?.product_option_values?.product_option_value || []);

      for (const av of attrValues) {
        const attrValData = await psGet('product_option_values', cleanId(av.id));
        const avData = attrValData?.prestashop?.product_option_value;

        const groupName = groupMap[cleanId(avData?.id_attribute_group)] || 'Option';
        const valueName = extractText(avData?.name);

        if (!groupedVariants[groupName]) {
          groupedVariants[groupName] = new Set(); // Set pour éviter les doublons
        }
        groupedVariants[groupName].add(valueName);
      }
    }

    // Convertir les Sets en Tableaux pour Vue
    const variants = {};
    for (const key in groupedVariants) {
      variants[key] = Array.from(groupedVariants[key]);
    }

    return {
      raw: p,
      features,
      variants // Maintenant un objet { "Taille": [...], "Couleur": [...] }
    };

  } catch (error) {
    console.error("Error in psGetProductFullDetails:", error);
    throw error;
  }
}

const buildCartKey = (ownerId) => {
  const key = ownerId ? String(ownerId) : 'guest';
  return `${CART_STORAGE_KEY}_${key}`;
};

const loadCartState = (ownerId) => {
  try {
    const raw = localStorage.getItem(buildCartKey(ownerId));
    if (!raw) return { items: [], total: 0, count: 0 };
    const parsed = JSON.parse(raw);
    if (!parsed || !Array.isArray(parsed.items)) {
      return { items: [], total: 0, count: 0 };
    }
    return {
      items: parsed.items,
      total: Number(parsed.total) || 0,
      count: Number(parsed.count) || 0,
    };
  } catch {
    return { items: [], total: 0, count: 0 };
  }
};

const persistCart = (state) => {
  localStorage.setItem(buildCartKey(state.ownerId), JSON.stringify({
    items: state.items,
    total: Number(state.total) || 0,
    count: Number(state.count) || 0,
  }));
};

const initialCart = loadCartState(null);

export const cart = reactive({
  ownerId: null,
  items: initialCart.items,
  total: initialCart.total,
  count: initialCart.count,

  setOwner(ownerId) {
    this.ownerId = ownerId || null;
    const next = loadCartState(this.ownerId);
    this.items = next.items;
    this.total = next.total;
    this.count = next.count;
  },

  add(product, quantity = 1, variants = {}) {
    // On crée une clé unique pour gérer les mêmes produits avec des variantes différentes
    const variantKey = Object.values(variants).join('-');
    const cartId = `${product.id}-${variantKey}`;

    const existingItem = this.items.find(item => item.cartId === cartId);

    const qty = Number.isFinite(quantity) ? Math.max(1, quantity) : 1;

    if (existingItem) {
      existingItem.quantity += qty;
    } else {
      this.items.push({
        cartId,
        id: product.id,
        name: product.name,
        price: product.priceTTC,
        imageUrl: product.imageUrl,
        variants: variants,
        quantity: qty
      });
    }
    this.updateTotals();
  },

  remove(cartId) {
    this.items = this.items.filter(item => item.cartId !== cartId);
    this.updateTotals();
  },

  setQuantity(cartId, quantity) {
    const qty = Number.isFinite(quantity) ? Math.max(1, quantity) : 1;
    const item = this.items.find(entry => entry.cartId === cartId);
    if (!item) return;
    item.quantity = qty;
    this.updateTotals();
  },

  updateTotals() {
    this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    this.count = this.items.reduce((sum, item) => sum + item.quantity, 0);
    persistCart(this);
  },

  clear() {
    this.items = [];
    this.updateTotals();
  }
});

if (cart.items.length) {
  cart.updateTotals();
}

export async function psCreateCart(customerId, items, addressId) {
  const cartData = {
    prestashop: {
      cart: {
        id_customer: customerId,
        id_currency: DEFAULT_CURRENCY_ID,
        id_lang: DEFAULT_LANG_ID,
        id_shop: DEFAULT_SHOP_ID,
        id_shop_group: DEFAULT_SHOP_GROUP_ID,
        id_address_delivery: addressId,
        id_address_invoice: addressId,
        associations: {
          cart_rows: {
            // PrestaShop attend une liste d'objets cart_row
            cart_row: items.map(item => ({
              id_product: item.id,
              id_product_attribute: item.id_attribute || 0,
              id_address_delivery: addressId,
              quantity: item.quantity
            }))
          }
        }
      }
    }
  };

  // Note : PrestaShop est très sensible aux balises vides. 
  // Si id_product_attribute est 0, il faut s'assurer qu'il est envoyé.
  const xml = builder.build(cartData);
  const response = await psPost('carts', xml);
  return getXmlId(response);
}

export async function psCreateOrder({
  cartId,
  customerId,
  addressId,
  total,
  secureKey,
  carrierId = DEFAULT_CARRIER_ID,
  currencyId = DEFAULT_CURRENCY_ID,
  langId = DEFAULT_LANG_ID,
  shopId = DEFAULT_SHOP_ID,
  shopGroupId = DEFAULT_SHOP_GROUP_ID,
  paymentModule = COD_MODULE,
  paymentName = COD_PAYMENT,
  stateId = COD_STATE_ID,
}) {
  const safeTotal = Number(total) ? Number(total).toFixed(6) : '0.000000';

  const xml = `<prestashop><order>
    <id_address_delivery>${addressId}</id_address_delivery>
    <id_address_invoice>${addressId}</id_address_invoice>
    <id_cart>${cartId}</id_cart>
    <id_currency>${currencyId}</id_currency>
    <id_lang>${langId}</id_lang>
    <id_customer>${customerId}</id_customer>
    <id_carrier>${carrierId}</id_carrier>
    <id_shop>${shopId}</id_shop>
    <id_shop_group>${shopGroupId}</id_shop_group>

    <current_state>${stateId}</current_state>
    <module>${paymentModule}</module>
    <payment>${paymentName}</payment>

    <recyclable>0</recyclable>
    <gift>0</gift>
    <gift_message></gift_message>
    <mobile_theme>0</mobile_theme>

    <valid>0</valid>
    <invoice_number>0</invoice_number>
    <delivery_number>0</delivery_number>

    <total_paid>${safeTotal}</total_paid>
    <total_paid_real>${safeTotal}</total_paid_real>
    <total_paid_tax_incl>${safeTotal}</total_paid_tax_incl>
    <total_paid_tax_excl>${safeTotal}</total_paid_tax_excl>

    <total_products>${safeTotal}</total_products>
    <total_products_wt>${safeTotal}</total_products_wt>

    <total_shipping>0</total_shipping>
    <total_shipping_tax_incl>0</total_shipping_tax_incl>
    <total_shipping_tax_excl>0</total_shipping_tax_excl>

    <total_discounts>0</total_discounts>
    <total_discounts_tax_incl>0</total_discounts_tax_incl>
    <total_discounts_tax_excl>0</total_discounts_tax_excl>

    <total_wrapping>0</total_wrapping>
    <total_wrapping_tax_incl>0</total_wrapping_tax_incl>
    <total_wrapping_tax_excl>0</total_wrapping_tax_excl>

    <carrier_tax_rate>0</carrier_tax_rate>
    <conversion_rate>1</conversion_rate>
    <round_mode>2</round_mode>
    <round_type>2</round_type>

    <secure_key>${secureKey || ''}</secure_key>
  </order></prestashop>`;

  const response = await psPost('orders', xml);
  return getXmlId(response);
}