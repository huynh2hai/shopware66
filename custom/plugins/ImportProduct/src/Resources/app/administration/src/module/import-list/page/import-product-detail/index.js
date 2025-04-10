import template from './import-product-detail.html.twig';
const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('import-product-detail', {
   template,
   inject: [
       'repositoryFactory'
   ],
    mixins: [
      Mixin.getByName('notification')
    ],
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            bundles: null,
            isLoading: false,
            processSuccess: false,
            repository: null
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
           this.repository = this.repositoryFactory.create('product_import_log');
           this.getBundle();
       },
        getBundle() {
            const criteria = new Criteria();


            criteria.setIds([this.$route.params.id]);
            // criteria.addFilter(
            //     Criteria.equals('product.id', '01961eb3f3c871a18bb507abada4c62b')
            // );

            this.repository.search(criteria, Shopware.Context.api).then((result) => {
                this.bundles = result;
            });

        },
        getColumns() {
            return [{
                property: 'fileName',
                label: 'fileName',
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'status',
                label: 'Status',
                inlineEdit: 'string',
                allowResize: true
            },
                {
                property: 'totalRecords',
                label: 'Total Record',
                inlineEdit: 'string',
                allowResize: true
            }];
        }
    }
});
