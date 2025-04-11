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
            repository: null,
            intervalId: null
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

    mounted() {
        // Start the interval when the component is mounted
        this.startAutoFetch();
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
        startAutoFetch() {
            // Ensure no existing interval is running
            this.stopAutoFetch();

            // Run getBundle immediately and then every 1 second
            this.getBundle();
            this.intervalId = setInterval(() => {
                if (this.$route.name === 'import.product.detail') {
                    // Only fetch if this component is active
                    this.getBundle();
                } else {
                    // Stop fetching if the route changes
                    this.stopAutoFetch();
                }
            }, 1000); // 1000ms = 1 second
        },

        stopAutoFetch() {
            // Clear the interval if it exists
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
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
            },
                {
                    property: 'importDetails',
                    label: 'Import Details',
                    rawData: true,
                    allowResize: true
                }];
        }
    }
});
