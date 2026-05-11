import type { ResourceConfigMap } from '../types/import.types';

export const RESOURCE_CONFIGS: ResourceConfigMap = {
    products: {
        label: 'Products',
        resourceName: 'products',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'product',
        apiEndpoint: '/api/products',
        httpMethod: 'POST',

        defaultLanguageId: 1,

        // champs traduisibles
        languageFields: [
            'name',
            'description',
            'description_short',
            'link_rewrite',
            'meta_title'
        ],

        mapping: {
            productName: 'name',
            nom: 'name',
            reference: 'reference',
            price: 'price',
            prix_ttc: 'price',
            active: 'active',

            prix_achat: 'wholesale_price',
            date_availability_produit: 'available_date',

            description: 'description',
            shortDescription: 'description_short',

            linkRewrite: 'link_rewrite',
            metaTitle: 'meta_title',

            visibility: 'visibility',
            state: 'state',
            minimalQuantity: 'minimal_quantity',
            Taxe: 'id_tax_rules_group',

            // ⚠️ IMPORTANT PRESTASHOP
            idCategoryDefault: 'id_category_default',
            idDefaultCategory: 'id_default_category',
            categorie: 'id_category_default',
            categoryIds: 'associations/categories/category[]/@id'
        },

        requiredFields: [
            'productName',
            'price',
            'idCategoryDefault'
        ],

        fieldTypes: {
            price: 'number',
            prix_ttc: 'number',
            prix_achat: 'number',
            active: 'boolean',
            idCategoryDefault: 'integer',
            date_availability_produit: 'date'
        },

        defaultValues: {
            active: '1',

            price: '0',
            idCategoryDefault: '2',
            idDefaultCategory: '2',

            state: '1',
            minimalQuantity: '1',

            quantity: '1',

            visibility: 'both',

            linkRewrite: 'product',
            metaTitle: 'Imported product',

            description: 'Imported product',
            shortDescription: 'Imported product'
        },

        listSeparator: '|',
        batchSize: 20
    },

    customers: {
        label: 'Customers',
        resourceName: 'customers',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'customer',
        apiEndpoint: '/api/customers',
        httpMethod: 'POST',
        mapping: {
            nom: 'lastname',
            firstName: 'firstname',
            lastName: 'lastname',
            email: 'email',
            password: 'passwd',
            pwd: 'passwd',
            active: 'active',
            group: 'id_default_group',
            language: 'id_lang'
        },
        requiredFields: [
            'nom',
            'email',
            'pwd'
        ],
        fieldTypes: {
            active: 'boolean',
            group: 'integer',
            language: 'integer'
        },
        defaultValues: {
            firstName: 'Client',
            active: '1',
            group: '3',
            language: '1'
        },
        listSeparator: '|',
        batchSize: 20
    },

    orders: {
        label: 'Orders',
        resourceName: 'orders',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'order',
        apiEndpoint: '/api/orders',
        httpMethod: 'POST',
        mapping: {
            date: 'date_add',
            etat: 'current_state',
            customerId: 'id_customer',
            deliveryAddressId: 'id_address_delivery',
            invoiceAddressId: 'id_address_invoice',
            payment: 'payment',
            totalHt: 'total_paid_tax_excl',
            totalTtc: 'total_paid_tax_incl',
            currency: 'id_currency',
            state: 'current_state'
        },
        requiredFields: [
            'date',
            'etat'
        ],
        fieldTypes: {
            date: 'date',
            totalHt: 'number',
            totalTtc: 'number'
        },
        defaultValues: {
            payment: 'Cash on delivery',
            currency: '1'
        },
        listSeparator: '|',
        batchSize: 10
    },

    categories: {
        label: 'Categories',
        resourceName: 'categories',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'category',
        apiEndpoint: '/api/categories',
        httpMethod: 'POST',
        mapping: {
            categoryName: 'name/language@value',
            parentId: 'id_parent',
            active: 'active'
        },
        requiredFields: ['categoryName'],
        fieldTypes: {
            parentId: 'integer',
            active: 'boolean'
        },
        listSeparator: '|',
        batchSize: 20
    },

    brands: {
        label: 'Brands',
        resourceName: 'manufacturers',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'manufacturer',
        apiEndpoint: '/api/manufacturers',
        httpMethod: 'POST',
        mapping: {
            brandName: 'name',
            active: 'active'
        },
        requiredFields: ['brandName'],
        fieldTypes: {
            active: 'boolean'
        },
        listSeparator: '|',
        batchSize: 20
    },

    suppliers: {
        label: 'Suppliers',
        resourceName: 'suppliers',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'supplier',
        apiEndpoint: '/api/suppliers',
        httpMethod: 'POST',
        mapping: {
            supplierName: 'name',
            active: 'active'
        },
        requiredFields: ['supplierName'],
        fieldTypes: {
            active: 'boolean'
        },
        listSeparator: '|',
        batchSize: 20
    },

    variants: {
        label: 'Product Variants',
        resourceName: 'combinations',
        xmlRootTag: 'prestashop',
        xmlItemTag: 'combination',
        apiEndpoint: '/api/combinations',
        httpMethod: 'POST',
        mapping: {
            reference: 'reference',
            specificité: 'reference',
            karazany: 'reference',
            stock_initial: 'quantity',
            prix_vente_ttc: 'price'
        },
        requiredFields: ['reference'],
        fieldTypes: {
            stock_initial: 'integer',
            prix_vente_ttc: 'number'
        },
        listSeparator: '|',
        batchSize: 20
    }
};

export const RESOURCE_KEYS = Object.keys(RESOURCE_CONFIGS);