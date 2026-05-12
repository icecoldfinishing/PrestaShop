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
            reference: 'reference',
            price: 'price',
            active: 'active',

            description: 'description',
            shortDescription: 'description_short',

            linkRewrite: 'link_rewrite',
            metaTitle: 'meta_title',

            visibility: 'visibility',
            state: 'state',
            minimalQuantity: 'minimal_quantity',

            // ⚠️ IMPORTANT PRESTASHOP
            idCategoryDefault: 'id_category_default',
            idDefaultCategory: 'id_default_category',
            categoryIds: 'associations/categories/category[]/@id'
        },

        requiredFields: [
            'productName',
            'price',
            'idCategoryDefault'
        ],

        fieldTypes: {
            price: 'number',
            active: 'boolean',
            idCategoryDefault: 'integer'
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
};

export const RESOURCE_KEYS = Object.keys(RESOURCE_CONFIGS);