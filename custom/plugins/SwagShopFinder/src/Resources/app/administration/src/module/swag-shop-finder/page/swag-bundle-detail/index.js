import template from './swag-bundle-detail.html.twig';
const { Component, Mixin } = Shopware;

Component.register('swag-bundle-detail', {
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
            bundle: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    computed: {
       options() {
            return [
                {value: 'absolute', name: 'Absolute'},
                {value: 'percentage', name: 'Percentage'},
            ];
       }
    },

    created() {
       this.createdComponent();
    },

    methods: {
       createdComponent() {
           this.repository = this.repositoryFactory.create('swag_shop_finder');
           this.getBundle();
       },
        getBundle() {
            this.repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                this.bundle = entity;
            });
        },
        onClickSave() {
            this.isLoading = true;

            this.repository.save(this.bundle, Shopware.Context.api).then(() => {
                this.getBundle();
                this.isLoading = false;
                this.processSuccess = true;
            }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: 'Save Errorrrrr',
                    message: exception
                });
            });

        },
        saveFinish() {
            this.processSuccess = false;
        }
    }
});
