import type {
  ResetModuleConfig,
  ResetModuleConfigMap
} from '../types/reset.types';

// ---------------------------------------------------------------------------
// CUSTOMER MODULE
// ---------------------------------------------------------------------------

const CUSTOMER_MODULE: ResetModuleConfig = {
  id: 'customers',
  label: 'Customers',
  description: 'Clients, adresses, connexions et invités',
  icon: 'bi-people-fill',
  defaultChecked: true,

  apiResources: [
    {
      apiResource: 'addresses',
      table: 'ps_address',
      singularTag: 'address'
    },
    {
      apiResource: 'customers',
      table: 'ps_customer',
      singularTag: 'customer'
    },
  ],

  cascadeTables: [
    { table: 'ps_connections', cascadeParent: 'customers' },
    { table: 'ps_connections_page', cascadeParent: 'customers' },
    { table: 'ps_connections_source', cascadeParent: 'customers' },
    { table: 'ps_guest', cascadeParent: 'customers' },
  ],

  dependsOn: [],
};

// ---------------------------------------------------------------------------
// ORDER MODULE
// ---------------------------------------------------------------------------

const ORDER_MODULE: ResetModuleConfig = {
  id: 'orders',
  label: 'Orders',
  description: 'Commandes, paiements, factures et paniers',
  icon: 'bi-cart-fill',
  defaultChecked: true,

  apiResources: [
    {
      apiResource: 'order_details',
      table: 'ps_order_detail',
      singularTag: 'order_detail'
    },
    {
      apiResource: 'order_histories',
      table: 'ps_order_history',
      singularTag: 'order_history'
    },
    {
      apiResource: 'order_payments',
      table: 'ps_order_payment',
      singularTag: 'order_payment'
    },
    {
      apiResource: 'order_slip',
      table: 'ps_order_slip',
      singularTag: 'order_slip'
    },
    {
      apiResource: 'orders',
      table: 'ps_orders',
      singularTag: 'order'
    },
    {
      apiResource: 'carts',
      table: 'ps_cart',
      singularTag: 'cart'
    },
  ],

  cascadeTables: [
    { table: 'ps_cart_product', cascadeParent: 'carts' },

    { table: 'ps_order_invoice', cascadeParent: 'orders' },
    { table: 'ps_order_invoice_payment', cascadeParent: 'orders' },

    { table: 'ps_order_return', cascadeParent: 'orders' },
    { table: 'ps_order_return_detail', cascadeParent: 'orders' },

    { table: 'ps_order_slip_detail', cascadeParent: 'orders' },

    { table: 'ps_order_carrier', cascadeParent: 'orders' },
    { table: 'ps_order_detail_tax', cascadeParent: 'orders' },

    { table: 'ps_message', cascadeParent: 'orders' },
    { table: 'ps_customer_thread', cascadeParent: 'orders' },
  ],

  dependsOn: [],
};

// ---------------------------------------------------------------------------
// PRODUCT MODULE
// ---------------------------------------------------------------------------

const PRODUCT_MODULE: ResetModuleConfig = {
  id: 'products',
  label: 'Products',
  description: 'Produits, déclinaisons, images, stocks et attributs',
  icon: 'bi-box-seam-fill',
  defaultChecked: true,

  apiResources: [
    {
      apiResource: 'stock_availables',
      table: 'ps_stock_available',
      singularTag: 'stock_available'
    },

    {
      apiResource: 'combinations',
      table: 'ps_product_attribute',
      singularTag: 'combination'
    },

    {
      apiResource: 'products',
      table: 'ps_product',
      singularTag: 'product'
    },
  ],

  cascadeTables: [

    // -------------------------------------------------------------------
    // PRODUITS
    // -------------------------------------------------------------------

    { table: 'ps_product_lang', cascadeParent: 'products' },
    { table: 'ps_product_shop', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // IMAGES
    // -------------------------------------------------------------------

    { table: 'ps_image', cascadeParent: 'products' },
    { table: 'ps_image_lang', cascadeParent: 'products' },
    { table: 'ps_image_shop', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // CATÉGORIES
    // -------------------------------------------------------------------

    { table: 'ps_category_product', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // FEATURES
    // -------------------------------------------------------------------

    { table: 'ps_feature_product', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // TAGS
    // -------------------------------------------------------------------

    { table: 'ps_product_tag', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // STOCKS
    // -------------------------------------------------------------------

    { table: 'ps_stock_available', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // DECLINAISONS
    // -------------------------------------------------------------------

    { table: 'ps_product_attribute_shop', cascadeParent: 'combinations' },

    { table: 'ps_product_attribute_combination', cascadeParent: 'combinations' },

    { table: 'ps_product_attribute_image', cascadeParent: 'combinations' },

    { table: 'ps_product_attribute_shop', cascadeParent: 'combinations' },

    // -------------------------------------------------------------------
    // SPECIFIC PRICES
    // -------------------------------------------------------------------

    { table: 'ps_specific_price', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // ACCESSOIRES
    // -------------------------------------------------------------------

    { table: 'ps_accessory', cascadeParent: 'products' },

    // -------------------------------------------------------------------
    // CUSTOMIZATION
    // -------------------------------------------------------------------

    { table: 'ps_customization_field', cascadeParent: 'products' },

    { table: 'ps_customization_field_lang', cascadeParent: 'products' },
  ],

  dependsOn: [],
};

// ---------------------------------------------------------------------------
// EXPORTS
// ---------------------------------------------------------------------------

export const RESET_MODULES: ResetModuleConfigMap = {
  customers: CUSTOMER_MODULE,
  orders: ORDER_MODULE,
  products: PRODUCT_MODULE,
};

export const RESET_MODULE_IDS = Object.keys(RESET_MODULES);

// ---------------------------------------------------------------------------
// EXECUTION ORDER
// ---------------------------------------------------------------------------

export const MODULE_EXECUTION_ORDER: string[] = [
  'orders',
  'customers',
  'products',
];

// ---------------------------------------------------------------------------
// HELPERS
// ---------------------------------------------------------------------------

export function getExecutionOrder(
  selectedModuleIds: string[]
): string[] {
  return MODULE_EXECUTION_ORDER.filter((id) =>
    selectedModuleIds.includes(id)
  );
}

export function getAllTablesForModule(
  moduleId: string
): string[] {
  const mod = RESET_MODULES[moduleId];

  if (!mod) return [];

  const apiTables = mod.apiResources.map((r) => r.table);

  const cascadeTables = mod.cascadeTables.map((c) => c.table);

  return [...apiTables, ...cascadeTables];
}