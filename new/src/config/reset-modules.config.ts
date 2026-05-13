import type { ResetModuleConfig, ResetModuleConfigMap } from '../types/reset.types';

// ---------------------------------------------------------------------------
// Centralized reset module configuration
// ---------------------------------------------------------------------------
// All module definitions live HERE. The reset service, import composable,
// and UI components read from this single source of truth.
//
// To add a new module:
//   1. Add a new entry to RESET_MODULES below.
//   2. That's it — the rest of the system picks it up automatically.
// ---------------------------------------------------------------------------

/**
 * Customer Module
 *
 * Deletion order (children first):
 *   addresses → customers
 *
 * Cascade tables (cleaned automatically by DB / API):
 *   ps_connections, ps_connections_page, ps_connections_source, ps_guest
 */
const CUSTOMER_MODULE: ResetModuleConfig = {
  id: 'customers',
  label: 'Customers',
  description: 'Clients, adresses, connexions et invités',
  icon: 'bi-people-fill',
  defaultChecked: true,

  apiResources: [
    { apiResource: 'addresses',  table: 'ps_address',   singularTag: 'address'  },
    { apiResource: 'customers',  table: 'ps_customer',  singularTag: 'customer' },
  ],

  cascadeTables: [
    { table: 'ps_connections',        cascadeParent: 'customers' },
    { table: 'ps_connections_page',   cascadeParent: 'customers' },
    { table: 'ps_connections_source', cascadeParent: 'customers' },
    { table: 'ps_guest',             cascadeParent: 'customers' },
  ],

  dependsOn: [],
};

/**
 * Order Module
 *
 * Deletion order (children first):
 *   order_details → order_histories → order_payments → order_slip → orders → carts
 *
 * Cascade tables:
 *   ps_order_invoice, ps_order_invoice_payment, ps_order_return,
 *   ps_order_return_detail, ps_order_slip_detail, ps_cart_product
 */
const ORDER_MODULE: ResetModuleConfig = {
  id: 'orders',
  label: 'Orders',
  description: 'Commandes, factures, paiements, retours et paniers',
  icon: 'bi-cart-fill',
  defaultChecked: true,

  apiResources: [
    { apiResource: 'order_details',   table: 'ps_order_detail',   singularTag: 'order_detail'  },
    { apiResource: 'order_histories', table: 'ps_order_history',  singularTag: 'order_history' },
    { apiResource: 'order_payments',  table: 'ps_order_payment',  singularTag: 'order_payment' },
    { apiResource: 'order_slip',      table: 'ps_order_slip',     singularTag: 'order_slip'    },
    { apiResource: 'orders',          table: 'ps_orders',         singularTag: 'order'         },
    { apiResource: 'carts',           table: 'ps_cart',           singularTag: 'cart'          },
  ],

  cascadeTables: [
    { table: 'ps_order_invoice',         cascadeParent: 'orders' },
    { table: 'ps_order_invoice_payment', cascadeParent: 'orders' },
    { table: 'ps_order_return',          cascadeParent: 'orders' },
    { table: 'ps_order_return_detail',   cascadeParent: 'orders' },
    { table: 'ps_order_slip_detail',     cascadeParent: 'order_slip' },
    { table: 'ps_cart_product',          cascadeParent: 'carts' },
  ],

  dependsOn: [],
};

/**
 * Product Module
 *
 * Deletion order (children first):
 *   stock_availables → images → products
 *
 * Cascade tables:
 *   ps_product_lang, ps_product_shop, ps_image_lang,
 *   ps_image_shop, ps_category_product
 */
const PRODUCT_MODULE: ResetModuleConfig = {
  id: 'products',
  label: 'Products',
  description: 'Produits, images, stocks et associations catégories',
  icon: 'bi-box-seam-fill',
  defaultChecked: true,

  apiResources: [
    { apiResource: 'stock_availables', table: 'ps_stock_available', singularTag: 'stock_available' },
    { apiResource: 'products',         table: 'ps_product',         singularTag: 'product'         },
  ],

  cascadeTables: [
    { table: 'ps_product_lang',     cascadeParent: 'products' },
    { table: 'ps_product_shop',     cascadeParent: 'products' },
    { table: 'ps_image',            cascadeParent: 'products' },
    { table: 'ps_image_lang',       cascadeParent: 'products' },
    { table: 'ps_image_shop',       cascadeParent: 'products' },
    { table: 'ps_category_product', cascadeParent: 'products' },
  ],

  dependsOn: [],
};

// ---------------------------------------------------------------------------
// Exported configuration map
// ---------------------------------------------------------------------------

/** All reset modules, keyed by module ID. */
export const RESET_MODULES: ResetModuleConfigMap = {
  customers: CUSTOMER_MODULE,
  orders:    ORDER_MODULE,
  products:  PRODUCT_MODULE,
};

/** Ordered array of module IDs (determines default display order). */
export const RESET_MODULE_IDS = Object.keys(RESET_MODULES);

/**
 * Global execution order for modules.
 *
 * When multiple modules are selected, they are executed in this order
 * to respect inter-module foreign key dependencies:
 *   1. Orders  (references customers + products)
 *   2. Customers
 *   3. Products
 */
export const MODULE_EXECUTION_ORDER: string[] = ['orders', 'customers', 'products'];

/**
 * Returns the ordered list of modules to execute based on selection.
 * Filters MODULE_EXECUTION_ORDER to only include selected module IDs.
 */
export function getExecutionOrder(selectedModuleIds: string[]): string[] {
  return MODULE_EXECUTION_ORDER.filter((id) => selectedModuleIds.includes(id));
}

/**
 * Returns all tables (API + cascade) for a given module.
 * Useful for displaying the full list of affected tables in the UI.
 */
export function getAllTablesForModule(moduleId: string): string[] {
  const mod = RESET_MODULES[moduleId];
  if (!mod) return [];

  const apiTables = mod.apiResources.map((r) => r.table);
  const cascadeTables = mod.cascadeTables.map((c) => c.table);
  return [...apiTables, ...cascadeTables];
}
