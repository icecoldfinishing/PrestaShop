import { reactive, computed } from "vue";
import { psGet, psPut } from "../prestashop-api";
import { cleanId , getXmlText } from "../products/product-api";
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
