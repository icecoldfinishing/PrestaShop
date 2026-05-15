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
  const response = await axios.put(`${BASE_URL}/${resource}`, xmlData, {
    params: {
      ws_key: API_KEY,
    },
    headers: {
      'Content-Type': 'application/xml',
    },
    responseType: 'text',
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
 * Commandes légères pour stats / tableau de bord (id, montant, date, état).
 * @returns {Promise<Array<{ id: string, total_paid: number, date_add: string, current_state: string }>>}
 */
export async function psGetOrdersLight() {
  const data = await psGet('orders', '', {
    display: '[id,total_paid,date_add,current_state]',
  });
  const raw = data?.prestashop?.orders?.order;
  if (!raw) return [];
  const list = Array.isArray(raw) ? raw : [raw];
  return list.map((o) => ({
    id: getXmlText(o.id),
    total_paid: parseFloat(getXmlText(o.total_paid) || '0') || 0,
    date_add: getXmlText(o.date_add),
    current_state: getXmlText(o.current_state),
  }));
}

/** Partie date YYYY-MM-DD d'une date PrestaShop (`date_add`). */
export function psOrderDateKey(dateAdd) {
  const s = String(dateAdd || '').trim();
  if (!s) return '';
  return s.slice(0, 10);
}

/**
 * Agrège les commandes par jour + totaux globaux.
 * @param {Array<{ total_paid: number, date_add: string }>} orders
 * @returns {{ byDay: Array<{ date: string, count: number, amount: number }>, totals: { count: number, amount: number } }}
 */
export function psAggregateOrdersByDay(orders) {
  const list = Array.isArray(orders) ? orders : [];
  const map = new Map();
  for (const o of list) {
    const day = psOrderDateKey(o.date_add);
    if (!day) continue;
    const prev = map.get(day) || { count: 0, amount: 0 };
    prev.count += 1;
    prev.amount += Number(o.total_paid) || 0;
    map.set(day, prev);
  }
  const byDay = [...map.entries()]
    .map(([date, v]) => ({ date, count: v.count, amount: v.amount }))
    .sort((a, b) => b.date.localeCompare(a.date));
  const totals = list.reduce(
    (acc, o) => {
      acc.count += 1;
      acc.amount += Number(o.total_paid) || 0;
      return acc;
    },
    { count: 0, amount: 0 }
  );
  return { byDay, totals };
}

/** Données tableau de bord : série par jour + totaux. */
export async function psGetOrdersDashboardStats() {
  const orders = await psGetOrdersLight();
  return psAggregateOrdersByDay(orders);
}

/** URL publique PrestaShop (images hors proxy). */
export const PS_PUBLIC_ORIGIN =
  import.meta.env.VITE_PRESTASHOP_PUBLIC_ORIGIN || 'http://localhost:8088';

/**
 * Clients actifs pour sélection FO (sans mot de passe).
 * @returns {Promise<Array<{ id: string, email: string, firstname: string, lastname: string }>>}
 */
export async function psGetActiveCustomersBrief() {
  const data = await psGet('customers', '', {
    display: '[id,email,firstname,lastname,active]',
    'filter[active]': '[1]',
  });
  const raw = data?.prestashop?.customers?.customer;
  if (!raw) return [];
  const list = Array.isArray(raw) ? raw : [raw];
  return list.map((c) => ({
    id: getXmlText(c.id),
    email: getXmlText(c.email),
    firstname: getXmlText(c.firstname),
    lastname: getXmlText(c.lastname),
  }));
}

/**
 * Catégories actives (id + nom) pour filtres FO.
 * @returns {Promise<Array<{ id: string, name: string }>>}
 */
export async function psGetCategoriesBrief() {
  const data = await psGet('categories', '', {
    display: '[id,name,id_parent,active]',
    'filter[active]': '[1]',
  });
  const raw = data?.prestashop?.categories?.category;
  if (!raw) return [];
  const list = Array.isArray(raw) ? raw : [raw];
  return list
    .map((cat) => {
      const id = getXmlText(cat.id);
      if (!id || id === '1') return null;
      const nameField = cat.name?.language;
      const name = Array.isArray(nameField) ? getXmlText(nameField[0]) : getXmlText(nameField);
      return { id, name: name || `Catégorie ${id}` };
    })
    .filter(Boolean);
}

/**
 * Badge produit selon date de disponibilité (`available_date`) ou repli sur `date_add`.
 * HOT : disponibilité / création dans les dernières 24 h.
 * NEW : entre 24 h et 7 jours (strictement après la fenêtre HOT).
 */
export function foProductBadgeFromAvailability(availableDate, dateAdd) {
  const parseDay = (s) => {
    const t = String(s || '').trim().slice(0, 10);
    if (!t || t.startsWith('0000')) return null;

    const [y, m, d] = t.split('-');
    if (!y || !m || !d) return null;

    // Force une date "pure" à minuit UTC-like local safe
    return new Date(Number(y), Number(m) - 1, Number(d));
  };

  const ref = parseDay(availableDate) || parseDay(dateAdd);
  if (!ref) return null;

  const today = new Date();
  const now = new Date(today.getFullYear(), today.getMonth(), today.getDate());

  const ms = now.getTime() - ref.getTime();
  if (ms < 0) return null;

  const days = ms / 86400000;

  if (days <= 1) return 'HOT';
  if (days <= 7) return 'NEW';
  return null;
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

export async function psLoginCustomerWithoutPassword(email) {
  if (!email) {
    throw new Error('Email requis.');
  }
  const data = await psGet('customers', '', {
    'filter[email]': `[${email}]`,
    display: 'full',
  });
  const customerData = data?.prestashop?.customers?.customer;
  const customer = Array.isArray(customerData) ? customerData[0] : customerData;
  if (!customer) {
    throw new Error('Email non trouvé.');
  }
  return {
    id: customer.id,
    email: getXmlText(customer.email),
    firstname: getXmlText(customer.firstname),
    lastname: getXmlText(customer.lastname),
  };
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
  if (xml == null) return '';
  const str = typeof xml === 'string' ? xml : String(xml);
  if (!str.trim()) return '';
  const doc = new DOMParser().parseFromString(str, 'text/xml');
  const orderId = doc.querySelector('prestashop > order > id')?.textContent?.trim();
  if (orderId) return orderId;
  return doc.getElementsByTagName('id')[0]?.textContent?.trim() || '';
}

export async function psGetCustomerSecureKey(customerId) {
  if (!customerId) return '';
  const data = await psGet('customers', customerId, { display: '[id,secure_key]' });
  return getXmlText(data?.prestashop?.customer?.secure_key) || '';
}

async function psGetTaxMultiplier(taxRulesGroupId) {
  try {
    const resRules = await psGet('tax_rules', '', {
      display: '[id_tax]',
      'filter[id_tax_rules_group]': `[${taxRulesGroupId}]`
    });
    const rule = resRules?.prestashop?.tax_rules?.tax_rule;
    const idTax = Array.isArray(rule) ? getXmlText(rule[0]?.id_tax) : getXmlText(rule?.id_tax);
    if (!idTax) return 1;

    const resTax = await psGet('taxes', idTax);
    const rate = parseFloat(getXmlText(resTax?.prestashop?.tax?.rate) || '0');
    if (!Number.isFinite(rate)) return 1;
    return 1 + (rate / 100);
  } catch {
    return 1;
  }
}

export async function psGetProductTaxMultiplier(product) {
  const groupId = getXmlText(product?.id_tax_rules_group || product?.id_tax_rules_group_id);
  if (!groupId) return 1;
  return psGetTaxMultiplier(groupId);
}

/** Clé de sécurité du panier — obligatoire sur une commande liée à ce panier (pas la clé client). */
export async function psGetCartSecureKey(cartId) {
  if (!cartId) return '';
  const data = await psGet('carts', cartId, { display: '[id,secure_key]' });
  return getXmlText(data?.prestashop?.cart?.secure_key) || '';
}

/**
 * Retourne un panier client non commandé (id_cart sans commande associée).
 * Prend le plus récent si plusieurs paniers existent.
 */
export async function psFindOpenCartId(customerId) {
  if (!customerId) return null;
  const data = await psGet('carts', '', {
    display: '[id,id_customer,date_add]','filter[id_customer]': `[${customerId}]`,
  });
  const raw = data?.prestashop?.carts?.cart;
  if (!raw) return null;
  const list = Array.isArray(raw) ? raw : [raw];
  const sorted = list
    .map((c) => ({ id: cleanId(c.id || c['@_id']), date: getXmlText(c.date_add) }))
    .filter((c) => c.id)
    .sort((a, b) => String(b.id).localeCompare(String(a.id)));

  for (const cart of sorted) {
    const orders = await psGet('orders', '', {
      display: '[id]',
      'filter[id_cart]': `[${cart.id}]`,
    });
    const orderRaw = orders?.prestashop?.orders?.order;
    const hasOrder = Array.isArray(orderRaw) ? orderRaw.length > 0 : !!orderRaw;
    if (!hasOrder) return cart.id;
  }
  return null;
}

/** Charge les lignes d'un panier PS et reconstruit le panier local. */
export async function psLoadCartItems(cartId) {
  if (!cartId) return [];
  const data = await psGet('carts', cartId, { display: 'full' });
  const cartData = data?.prestashop?.cart;
  const rawRows = [].concat(cartData?.associations?.cart_rows?.cart_row || []).filter(Boolean);
  if (!rawRows.length) return [];

  const productIds = rawRows.map((row) => cleanId(row.id_product)).filter(Boolean);
  const products = await Promise.all(
    productIds.map((pid) => psGet('products', pid, { display: '[id,price,name,reference,id_tax_rules_group]' }))
  );

  return await Promise.all(rawRows.map(async (row, idx) => {
    const pid = cleanId(row.id_product);
    const attr = cleanId(row.id_product_attribute) || '0';
    const qty = Math.max(1, parseInt(getXmlText(row.quantity), 10) || 1);
    const p = products[idx]?.prestashop?.product;
    const priceHt = Number.parseFloat(getXmlText(p?.price) || '0') || 0;
    const taxMultiplier = await psGetProductTaxMultiplier(p);
    const impactHt = await psGetCombinationPriceImpact(attr);
    const priceHtWithImpact = priceHt + impactHt;
    const priceTtc = priceHtWithImpact * taxMultiplier;
    const name = extractText(p?.name) || 'Produit';
    const reference = getXmlText(p?.reference) || '';
    const cartIdKey = `${pid}-${attr}`;

    return {
      cartId: cartIdKey,
      id: pid,
      id_attribute: attr,
      name,
      reference,
      price: priceTtc,
      quantity: qty,
    };
  }));
}

async function psGetCombinationPriceImpact(combinationId) {
  const cleaned = cleanId(combinationId);
  if (!cleaned || cleaned === '0') return 0;
  try {
    const res = await psGet('combinations', cleaned, { display: '[id,price]' });
    const combo = res?.prestashop?.combination;
    return Number.parseFloat(getXmlText(combo?.price) || '0') || 0;
  } catch {
    return 0;
  }
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
    const groupedVariants = {};
    const combinations = [];

    for (const combo of pCombos) {
      const comboId = cleanId(combo.id);
      const comboDetail = await psGet('combinations', comboId);
      const comboData = comboDetail?.prestashop?.combination;
      const attrValues = [].concat(comboData?.associations?.product_option_values?.product_option_value || []);

      const optionMap = {};

      for (const av of attrValues) {
        const attrValData = await psGet('product_option_values', cleanId(av.id));
        const avData = attrValData?.prestashop?.product_option_value;

        const groupName = groupMap[cleanId(avData?.id_attribute_group)] || 'Option';
        const valueName = extractText(avData?.name);

        if (!groupedVariants[groupName]) {
          groupedVariants[groupName] = new Set();
        }
        groupedVariants[groupName].add(valueName);
        optionMap[groupName] = valueName;
      }

      combinations.push({
        id: comboId,
        reference: getXmlText(comboData?.reference),
        priceImpact: Number.parseFloat(getXmlText(comboData?.price) || '0') || 0,
        options: optionMap
      });
    }

    const variants = {};
    for (const key in groupedVariants) {
      variants[key] = Array.from(groupedVariants[key]);
    }

    return {
      raw: p,
      features,
      variants,
      combinations
    };

  } catch (error) {
    console.error("Error in psGetProductFullDetails:", error);
    throw error;
  }
}

// Dans prestashop-api.js

let ownerSwitchToken = 0;

export const cart = reactive({
  ownerId: null,
  psCartId: null, // On stocke l'ID PrestaShop ici
  items: [],
  total: 0,
  count: 0,

  // Dans l'objet cart de prestashop-api.js
  // Dans l'objet cart de prestashop-api.js
  async setOwner(ownerId) {
    const nextOwner = ownerId ? String(ownerId) : null;
    if (this.ownerId === nextOwner) return;
    const token = ++ownerSwitchToken;
    // 1. Reset total de l'état en mémoire pour éviter les fuites de données
    this.items = [];
    this.psCartId = null;
    this.total = 0;
    this.count = 0;
    this.ownerId = nextOwner;

    // 2. Charger le panier PS non commandé
    if (this.ownerId) {
      this.psCartId = await psFindOpenCartId(this.ownerId);
    }

    // 3. Hydrater depuis PS si panier trouvé
    if (this.ownerId && this.psCartId) {
      this.items = await psLoadCartItems(this.psCartId);
    }

    if (token !== ownerSwitchToken) return;

    // 5. Mise à jour des totaux locaux
    this.updateTotals();
  },

  async add(product, quantity = 1, variants = {}) {
    const qty = Number.isFinite(quantity) ? Math.max(1, quantity) : 1;
    const variantKey = Object.values(variants).join('-');
    const cartId = `${product.id}-${variantKey}`;

    const existingItem = this.items.find(item => item.cartId === cartId);
    if (existingItem) {
      existingItem.quantity += qty;
    } else {
      this.items.push({
        cartId,
        id: product.id,
        id_attribute: product.id_attribute || 0, // Assure-toi d'avoir l'ID de déclinaison
        name: product.name,
        price: product.priceTTC,
        quantity: qty
      });
    }

    await this.syncWithPrestaShop();
  },

  async remove(cartId) {
    this.items = this.items.filter(item => item.cartId !== cartId);
    await this.syncWithPrestaShop();
  },

  async setQuantity(cartId, quantity) {
    const item = this.items.find(entry => entry.cartId === cartId);
    if (!item) return;
    item.quantity = Math.max(1, quantity);
    await this.syncWithPrestaShop();
  },

  // La fonction CLÉ : Synchronise l'état local vers la DB PrestaShop

  async syncWithPrestaShop() {
    if (!this.ownerId) {
      this.updateTotals();
      return;
    }

    const itemsForApi = this.items.map(item => ({
      id: item.id,
      quantity: item.quantity,
      id_attribute: item.id_attribute || 0
    }));

    try {
      const addressId = await psEnsureCustomerAddress({ id: this.ownerId });

      if (!this.psCartId) {
        this.psCartId = await psFindOpenCartId(this.ownerId);
      }

      if (this.psCartId) {
        // SI UN ID EXISTE : MISE À JOUR (PUT)
        console.log(`Mise à jour du panier PS #${this.psCartId}...`);
        await psUpdateCart(this.psCartId, this.ownerId, itemsForApi, addressId);
      } else {
        // SI PAS D'ID : CRÉATION (POST)
        console.log("Création d'un nouveau panier PS...");
        const newPsId = await psCreateCart(this.ownerId, itemsForApi, addressId);
        this.psCartId = newPsId;
      }

      console.log(`Panier PrestaShop synchronisé : #${this.psCartId}`);
      if (this.psCartId) {
        this.items = await psLoadCartItems(this.psCartId);
      }
    } catch (err) {
      console.error("Erreur synchro panier PrestaShop:", err);
    }

    this.updateTotals();
  },

  updateTotals() {
    this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    this.count = this.items.reduce((sum, item) => sum + item.quantity, 0);
  },

  clear() {
    this.items = [];
    this.psCartId = null;
    this.updateTotals();
  }
});

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

/**
 * Lignes commande + totaux depuis le panier boutique (prix FO).
 * Chaque élément : productId, productAttributeId?, quantity, name?, reference?, unitPriceTaxIncl?, unitPriceTaxExcl?.
 */
export function psBuildOrderRowsFromLineItems(lineItems) {
  const list = Array.isArray(lineItems) ? lineItems : [];
  let rowsXml = '';
  let sumWt = 0;
  let sumHt = 0;

  for (const li of list) {
    const qty = Math.max(1, Number(li.quantity) || 1);
    const incl = Number(li.unitPriceTaxIncl);
    const inclSafe = Number.isFinite(incl) ? incl : 0;
    const exclRaw = li.unitPriceTaxExcl;
    const excl = Number.isFinite(Number(exclRaw)) ? Number(exclRaw) : inclSafe;
    const pid = cleanId(li.productId ?? li.id);
    const attr = cleanId(li.productAttributeId ?? li.id_attribute ?? 0) || '0';
    const name = (li.name || 'Produit').replace(/]]>/g, '');
    const ref = String(li.reference || '').replace(/]]>/g, '');

    sumWt += inclSafe * qty;
    sumHt += excl * qty;

    rowsXml += `
      <order_row>
        <product_id>${pid}</product_id>
        <product_attribute_id>${attr}</product_attribute_id>
        <product_quantity>${qty}</product_quantity>
        <product_name><![CDATA[${name}]]></product_name>
        <product_reference><![CDATA[${ref}]]></product_reference>
        <product_price>${excl.toFixed(6)}</product_price>
        <unit_price_tax_incl>${inclSafe.toFixed(6)}</unit_price_tax_incl>
        <unit_price_tax_excl>${excl.toFixed(6)}</unit_price_tax_excl>
      </order_row>`;
  }

  return {
    rowsXml,
    totalPaidWt: sumWt,
    totalProducts: sumHt,
    totalProductsWt: sumWt,
  };
}

/**
 * Lignes commande + totaux depuis le panier PrestaShop (GET cart full + prix catalogue).
 */
export async function psBuildOrderRowsFromCartWebService(cartId) {
  const data = await psGet('carts', cartId, { display: 'full' });
  const c = data?.prestashop?.cart;
  if (!c) {
    throw new Error(`Panier #${cartId} introuvable (API).`);
  }

  const rawRows = [].concat(c?.associations?.cart_rows?.cart_row || []).filter(Boolean);
  if (!rawRows.length) {
    throw new Error(`Panier #${cartId} sans lignes (cart_rows).`);
  }

  let rowsXml = '';
  let sumWt = 0;
  let sumHt = 0;

  for (const row of rawRows) {
    const pid = cleanId(row.id_product);
    const attr = cleanId(row.id_product_attribute) || '0';
    const qty = Math.max(1, parseInt(getXmlText(row.quantity), 10) || 1);

    const pRes = await psGet('products', pid, { display: '[id,price,reference,name]' });
    const p = pRes?.prestashop?.product;
    if (!p) {
      throw new Error(`Produit #${pid} introuvable pour le panier #${cartId}.`);
    }

    const name = extractText(p.name) || 'Produit';
    const ref = getXmlText(p.reference) || '';
    const priceHt = Number.parseFloat(getXmlText(p.price) || '0') || 0;
    const priceTtc = priceHt;

    sumWt += priceTtc * qty;
    sumHt += priceHt * qty;

    rowsXml += `
      <order_row>
        <product_id>${pid}</product_id>
        <product_attribute_id>${attr}</product_attribute_id>
        <product_quantity>${qty}</product_quantity>
        <product_name><![CDATA[${name.replace(/]]>/g, '')}]]></product_name>
        <product_reference><![CDATA[${ref.replace(/]]>/g, '')}]]></product_reference>
        <product_price>${priceHt.toFixed(6)}</product_price>
        <unit_price_tax_incl>${priceTtc.toFixed(6)}</unit_price_tax_incl>
        <unit_price_tax_excl>${priceHt.toFixed(6)}</unit_price_tax_excl>
      </order_row>`;
  }

  return {
    rowsXml,
    totalPaidWt: sumWt,
    totalProducts: sumHt,
    totalProductsWt: sumWt,
  };
}


/**
 * Crée une commande PrestaShop. `secureKey` = clé du panier (GET carts).
 * Lignes : `orderRowsXml` si fourni, sinon `lineItems`, sinon relecture GET panier + produits.
 */
export async function psCreateOrder({
  cartId,
  customerId,
  addressId,
  total,
  secureKey,
  orderRowsXml,
  lineItems,
  carrierId = DEFAULT_CARRIER_ID,
  currencyId = DEFAULT_CURRENCY_ID,
  langId = DEFAULT_LANG_ID,
  shopId = DEFAULT_SHOP_ID,
  shopGroupId = DEFAULT_SHOP_GROUP_ID,
  paymentModule = COD_MODULE,
  paymentName = COD_PAYMENT,
  stateId = COD_STATE_ID,
}) {
  const key = (secureKey || '').trim();

  let rowsInner = orderRowsXml && String(orderRowsXml).trim();
  let totalWt;
  let totalHt;

  if (rowsInner) {
    const fallback = Number(total) && Number(total) > 0 ? Number(total).toFixed(6) : '1.000000';
    totalWt = fallback;
    totalHt = fallback;
  } else if (lineItems && lineItems.length) {
    const built = psBuildOrderRowsFromLineItems(lineItems);
    rowsInner = built.rowsXml;
    totalWt = built.totalProductsWt.toFixed(6);
    totalHt = built.totalProducts.toFixed(6);
  } else {
    const built = await psBuildOrderRowsFromCartWebService(cartId);
    rowsInner = built.rowsXml;
    totalWt = built.totalProductsWt.toFixed(6);
    totalHt = built.totalProducts.toFixed(6);
  }

  const associationsBlock = rowsInner
    ? `<associations><order_rows>${rowsInner}</order_rows></associations>`
    : '';

  const xml = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <order>
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
    <module><![CDATA[${paymentModule}]]></module>
    <payment><![CDATA[${paymentName}]]></payment>

    <valid>0</valid>
    <invoice_number>0</invoice_number>
    <delivery_number>0</delivery_number>
    <recyclable>0</recyclable>
    <gift>0</gift>
    <gift_message></gift_message>
    <mobile_theme>0</mobile_theme>

    <total_paid>${totalWt}</total_paid>
    <total_paid_real>${totalWt}</total_paid_real>
    <total_paid_tax_incl>${totalWt}</total_paid_tax_incl>
    <total_paid_tax_excl>${totalHt}</total_paid_tax_excl>

    <total_products>${totalHt}</total_products>
    <total_products_wt>${totalWt}</total_products_wt>

    <total_shipping>0.000000</total_shipping>
    <total_shipping_tax_incl>0.000000</total_shipping_tax_incl>
    <total_shipping_tax_excl>0.000000</total_shipping_tax_excl>

    <total_discounts>0.000000</total_discounts>
    <total_discounts_tax_incl>0.000000</total_discounts_tax_incl>
    <total_discounts_tax_excl>0.000000</total_discounts_tax_excl>

    <total_wrapping>0.000000</total_wrapping>
    <total_wrapping_tax_incl>0.000000</total_wrapping_tax_incl>
    <total_wrapping_tax_excl>0.000000</total_wrapping_tax_excl>

    <carrier_tax_rate>0.000000</carrier_tax_rate>
    <conversion_rate>1.000000</conversion_rate>
    <round_mode>2</round_mode>
    <round_type>2</round_type>

    <secure_key><![CDATA[${key}]]></secure_key>
    ${associationsBlock}
  </order>
</prestashop>`;

  const response = await psPost('orders', xml);
  const id = getXmlId(response);
  if (!id) {
    const errBody = extractPrestaShopErrorsFromXml(typeof response === 'string' ? response : String(response));
    throw new Error(errBody || 'Réponse POST /orders sans id commande (XML inattendu).');
  }
  return id;
}

/**
 * Met à jour un panier existant dans PrestaShop.
 */
export async function psUpdateCart(cartId, customerId, items, addressId) {
  const cartData = {
    prestashop: {
      cart: {
        id: cartId, // Requis pour le PUT
        id_customer: customerId,
        id_currency: DEFAULT_CURRENCY_ID,
        id_lang: DEFAULT_LANG_ID,
        id_shop: DEFAULT_SHOP_ID,
        id_shop_group: DEFAULT_SHOP_GROUP_ID,
        id_address_delivery: addressId,
        id_address_invoice: addressId,
        associations: {
          cart_rows: {
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

  const xml = builder.build(cartData);
  // On utilise psPut sur la ressource carts/{id}
  return psPut(`carts/${cartId}`, xml);
}

/**
 * ==========================================
 * STOCK MANAGEMENT
 * ==========================================
 */

/**
 * Récupère tous les stocks disponibles
 */
export async function psGetStockAvailables() {
  const data = await psGet('stock_availables', '', { display: 'full' });
  const raw = data?.prestashop?.stock_availables?.stock_available;
  if (!raw) return [];
  return Array.isArray(raw) ? raw : [raw];
}

/**
 * Récupère un stock disponible spécifique
 */
export async function psGetStockAvailable(stockId) {
  const data = await psGet('stock_availables', stockId);
  return data?.prestashop?.stock_available;
}

/**
 * Met à jour la quantité d'un stock
 */
export async function psUpdateStockAvailable(stockId, newQuantity) {
  // 1. Get the current stock data
  const currentStock = await psGetStockAvailable(stockId);
  if (!currentStock) throw new Error(`Stock #${stockId} not found`);

  // 2. Build the XML manually to avoid fast-xml-parser artifacts with xlink:href
  const xmlData = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <stock_available>
    <id>${stockId}</id>
    <id_product>${getXmlText(currentStock.id_product)}</id_product>
    <id_product_attribute>${getXmlText(currentStock.id_product_attribute)}</id_product_attribute>
    <id_shop>${getXmlText(currentStock.id_shop)}</id_shop>
    <id_shop_group>${getXmlText(currentStock.id_shop_group)}</id_shop_group>
    <quantity>${newQuantity}</quantity>
    <depends_on_stock>${getXmlText(currentStock.depends_on_stock)}</depends_on_stock>
    <out_of_stock>${getXmlText(currentStock.out_of_stock)}</out_of_stock>
    <location><![CDATA[${getXmlText(currentStock.location)}]]></location>
  </stock_available>
</prestashop>`;

  return psPut(`stock_availables/${stockId}`, xmlData);
}

/**
 * Récupère les mouvements de stock réels d'un produit basés sur les commandes acceptées
 */
export async function psGetStockMovementsFromOrders(productId, productAttributeId = '0') {
  try {
    const data = await psGet('orders', '', { display: 'full' });
    const raw = data?.prestashop?.orders?.order;
    if (!raw) return [];
    
    const orders = Array.isArray(raw) ? raw : [raw];
    const movements = [];
    
    for (const o of orders) {
      const stateId = cleanId(o.current_state);
      // Considère l'état "2" (Paiement accepté) comme validant le retrait de stock
      if (stateId !== '2') continue; 
      
      const rows = [].concat(o.associations?.order_rows?.order_row || []);
      for (const row of rows) {
        const rowProductId = cleanId(row.product_id);
        const rowAttrId = cleanId(row.product_attribute_id) || '0';

        if (rowProductId === String(productId) && rowAttrId === String(productAttributeId)) {
          movements.push({
            id: `order-${cleanId(o.id)}`,
            change: -Math.max(1, parseInt(getXmlText(row.product_quantity), 10) || 1),
            sign: -1,
            date: getXmlText(o.date_add),
            reason: `Commande #${cleanId(o.id)}`
          });
        }
      }
    }
    
    return movements.sort((a, b) => new Date(b.date) - new Date(a.date));
  } catch (error) {
    console.error("Error fetching orders for stock evolution:", error);
    return [];
  }
}