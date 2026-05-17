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

/**
 * Récupère les mouvements de stock réels
 */
export async function psGetStockMovementsFromOrders(
  productId,
  productAttributeId = '0'
) {
  try {
    const data = await psGet('orders', '', {
      display: 'full'
    });

    const raw = data?.prestashop?.orders?.order;

    if (!raw) return [];

    const orders = Array.isArray(raw) ? raw : [raw];

    const movements = [];

    for (const o of orders) {
      const stateId = cleanId(o.current_state);

      // Exclure uniquement les états Annulé (6) et Erreur de paiement (8)
      if (stateId === '6' || stateId === '8') continue;

      const rows = [].concat(
        o.associations?.order_rows?.order_row || []
      );

      for (const row of rows) {
        const rowProductId = cleanId(row.product_id);

        const rowAttrId =
          cleanId(row.product_attribute_id) || '0';

        if (
          rowProductId === String(productId) &&
          rowAttrId === String(productAttributeId)
        ) {
          movements.push({
            id: `order-${cleanId(o.id)}`,
            change:
              -Math.max(
                1,
                parseInt(
                  getXmlText(row.product_quantity),
                  10
                ) || 1
              ),
            sign: -1,
            date: getXmlText(o.date_add),
            reason: `Commande #${cleanId(o.id)}`
          });
        }
      }
    }

    return movements.sort(
      (a, b) =>
        new Date(b.date).getTime() -
        new Date(a.date).getTime()
    );
  } catch (error) {
    console.error(
      'Erreur récupération mouvements stock:',
      error
    );

    return [];
  }
}