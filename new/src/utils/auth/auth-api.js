import { reactive, computed } from "vue";
import { psGet, psPut } from "../prestashop-api";
import { getXmlText } from "../products/product-api";
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