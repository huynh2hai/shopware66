// <plugin root>/src/Resources/app/administration/src/module/swag-example/index.js
import './page/import-product-index';
import './page/import-product-detail';


Shopware.Module.register('import-product', {
    type: 'plugin',
    name: 'Import',
    title: 'Import Product',
    description: 'Import Product via CSV file',
    color: '#ff3d58',
    icon: 'preview-vimeo',

    routes: {
        index: {
            component: 'import-product-index',
            path: 'index'
        },
        detail: {
            component: 'import-product-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'import.product.index'
            }
        }
    },

    navigation: [{
        label: 'Import Product',
        color: '#ff3d58',
        path: 'import.product.index',
        icon: 'preview-vimeo',
        position: 81,
        parent: 'sw-extension'
    }]
});
