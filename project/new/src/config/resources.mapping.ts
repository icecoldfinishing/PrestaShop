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
        languageFields: ['name'],
        mapping: {
            productName: 'name',
            reference: 'reference',
            price: 'price',
            active: 'active',
            categoryIds: 'associations/categories/category[]/@id'
        },
        requiredFields: ['productName', 'price'],
        fieldTypes: {
            price: 'number',
            active: 'boolean'
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
            firstName: 'firstname',
            lastName: 'lastname',
            email: 'email',
            password: 'passwd',
            active: 'active',
            group: 'id_default_group',
            language: 'id_lang'
        },
        requiredFields: [
            'firstName',
            'lastName',
            'email',
            'password'
        ],
        fieldTypes: {
            active: 'boolean',
            group: 'integer',
            language: 'integer'
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
            'customerId',
            'deliveryAddressId',
            'invoiceAddressId',
            'payment'
        ],
        fieldTypes: {
            totalHt: 'number',
            totalTtc: 'number'
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
    }
};

export const RESOURCE_KEYS = Object.keys(RESOURCE_CONFIGS);