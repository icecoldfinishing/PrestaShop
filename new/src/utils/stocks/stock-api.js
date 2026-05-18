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
 * Utilise la ressource native stock_movements (POST)
 * ==========================================================
 */
export async function psUpdateStockAvailable(
  stockId,
  newQuantity,
  idMvtReason = 3 // Raison par défaut : 3 correspond généralement à une régularisation/réapprovisionnement
) {
  // 1. Charger le stock actuel pour calculer le delta
  const currentStock = await psGetStockAvailable(stockId);

  if (!currentStock) {
    throw new Error(`Stock #${stockId} introuvable`);
  }

  // 2. Quantité actuelle
  const currentQuantity = parseInt(
    getXmlText(currentStock.quantity) || '0',
    10
  );

  // 3. Calcul du delta physique
  const delta = newQuantity - currentQuantity;

  // 4. Rien à faire si le stock n'a pas bougé
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
  const idProductAttribute = cleanId(currentStock.id_product_attribute) || '0';

  // 5. Exécuter le mouvement de stock natif
  return await psUpdateStockByDelta(idProduct, delta, idProductAttribute, idMvtReason);
}

/**
 * ==========================================================
 * CRÉATION D'UN MOUVEMENT DE STOCK DIRECT (DELTA)
 * Envoie un POST sur /api/stock_movements
 * ==========================================================
 */
export async function psUpdateStockByDelta(
  idProduct,
  delta,
  idProductAttribute = 0,
  idMvtReason = 3 // 3 = Régularisation, changez selon vos besoins (Ex: 1 pour commande)
) {
  if (!delta || delta === 0) {
    throw new Error('Delta invalide');
  }

  // PrestaShop stock_movements prend une valeur absolue pour la quantité
  const absQty = Math.abs(delta);
  
  // Le signe du mouvement est déterminé par id_stock_mvt_reason ou par la direction native.
  // Note : Dans PrestaShop standard, un POST sur stock_movements décrémente ou incrémente 
  // selon le signe ou le type de gestion appliqué à la raison (id_stock_mvt_reason).
  // Si votre PrestaShop requiert une valeur positive/négative brute, utilisez `${delta}` à la place de `absQty`.
  
  const xmlData = `<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <stock_movement>
    <id_product>${idProduct}</id_product>
    <id_product_attribute>${idProductAttribute}</id_product_attribute>
    <id_warehouse>0</id_warehouse> <!-- 0 ou l'ID de votre entrepôt par défaut -->
    <id_stock_mvt_reason>${idMvtReason}</id_stock_mvt_reason>
    <physical_quantity>${absQty}</physical_quantity>
    <!-- Si vous diminuez le stock suite à une vente manuelle, signalez-le dans le sens du mouvement si nécessaire -->
    <sign>${delta > 0 ? 1 : -1}</sign> 
  </stock_movement>
</prestashop>`;

  const response = await axios.post(
    `${BASE_URL}/stock_movements?ws_key=${API_KEY}`,
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
    message: 'Mouvement de stock enregistré via stock_movements API',
  };
}