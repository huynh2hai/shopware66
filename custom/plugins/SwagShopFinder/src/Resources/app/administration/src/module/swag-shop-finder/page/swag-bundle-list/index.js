import template from './swag-bundle-list.html.twig';
const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('swag-bundle-list', {
    template,
    inject: [
        'repositoryFactory'
    ],
    data() {
        return {
            repository: null,
            bundles: null,
        }
    },
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    computed: {
        columns() {
            return this.getColumns();
        }
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create('swag_shop_finder');
            this.repository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.bundles = result;
            });
        },
        getColumns() {
            return [{
               property: 'name',
                label: 'Name',
                inlineEdit: 'string',
                allowResize: true,
                primary: true

            }, {
                property: 'street',
                label: 'street',
                inlineEdit: 'string',
                allowResize: true
            }];
        }
    }
});
