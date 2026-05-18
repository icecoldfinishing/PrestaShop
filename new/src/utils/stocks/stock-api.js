import { reactive, computed } from "vue";
import { psGet, psPut } from "../prestashop-api";
import { cleanId, getXmlText } from "../products/product-api";
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
 * ==========================================
 * STOCK MANAGEMENT
 * ==========================================
 */

/**
 * Récupère tous les stocks disponibles
 */
export async function psGetStockAvailables() {
  const data = await psGet('stock_availables', '', {
    display: 'full'
  });

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
 * ==========================================================
 * NOUVELLE VERSION
 * Utilise ton endpoint custom stock_deltas
 * ==========================================================
 */
export async function psUpdateStockAvailable(
  stockId,
  newQuantity
) {
  // 1. Charger le stock actuel
  const currentStock = await psGetStockAvailable(stockId);

  if (!currentStock) {
    throw new Error(`Stock #${stockId} introuvable`);
  }

  // 2. Quantité actuelle
  const currentQuantity = parseInt(
    getXmlText(currentStock.quantity) || '0',
    10
  );

  // 3. Calcul delta
  const delta = newQuantity - currentQuantity;

  // 4. Rien à faire
  if (delta === 0) {
    return {
      success: true,
      message: 'Aucune modification de stock',
      current_quantity: currentQuantity,
      new_quantity: newQuantity,
      delta: 0,
    };
  }

  const idProduct = cleanId(currentStock.id_product);
  const idProductAttribute =
    cleanId(currentStock.id_product_attribute) || '0';

  // 5. Construire XML du endpoint custom
  const xmlData = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <stock_delta>
    <id_product>${idProduct}</id_product>
    <id_product_attribute>${idProductAttribute}</id_product_attribute>
    <delta>${delta}</delta>
  </stock_delta>
</prestashop>`;

  // 6. Appel du endpoint custom
  const response = await axios.post(
    `${BASE_URL}/stock_deltas?ws_key=${API_KEY}`,
    xmlData,
    {
      headers: {
        'Content-Type': 'application/xml',
        Accept: 'application/xml',
      },
    }
  );

  return {
    success: true,
    response: response.data,
    id_product: idProduct,
    id_product_attribute: idProductAttribute,
    old_quantity: currentQuantity,
    new_quantity: newQuantity,
    delta,
    message: 'Stock mis à jour via stock_deltas',
  };
}

/**
 * ==========================================================
 * BONUS
 * Mise à jour directe par DELTA
 * ==========================================================
 */
export async function psUpdateStockByDelta(
  idProduct,
  delta,
  idProductAttribute = 0
) {
  if (!delta || delta === 0) {
    throw new Error('Delta invalide');
  }

  const xmlData = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <stock_delta>
    <id_product>${idProduct}</id_product>
    <id_product_attribute>${idProductAttribute}</id_product_attribute>
    <delta>${delta}</delta>
  </stock_delta>
</prestashop>`;

  const response = await axios.post(
    `${BASE_URL}/stock_deltas?ws_key=${API_KEY}`,
    xmlData,
    {
      headers: {
        'Content-Type': 'application/xml',
        Accept: 'application/xml',
      },
    }
  );

  return {
    success: true,
    response: response.data,
    id_product: idProduct,
    id_product_attribute: idProductAttribute,
    delta,
    message: 'Delta appliqué via stock_deltas',
  };
}

