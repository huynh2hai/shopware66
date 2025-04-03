import './page/swag-bundle-list';
import './page/swag-bundle-create';
import './page/swag-bundle-detail';

Shopware.Module.register('swag-example', {
    type: 'plugin',
    name: 'Example',
    title: 'swag-example.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',
    color: '#ff3d58',
    icon: 'default-shopping-paper-bag-product',
    //
    // snippets: {
    //     'de-DE': deDE,
    //     'en-GB': enGB
    // },
    //

    routes: {
        index: {
            component: 'swag-bundle-list',
            path: 'index'
        },
        detail: {
            component: 'swag-bundle-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'swag.example.index'
            }
        },
        create: {
            component: 'swag-bundle-create',
            path: 'create',
            meta: {
                parentPath: 'swag.example.index'
            }
        }
    },

    //
    navigation: [{
        label: 'swag-example.general.mainMenuItemGeneral',
        color: '#ff3d58',
        path: 'swag.example.index',
        icon: 'default-shopping-paper-bag-product',
        position: 100,
        parent: 'sw-extension'
    }]
});
