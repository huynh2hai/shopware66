const { Component } = Shopware;

Component.extend('swag-bundle-create', 'swag-bundle-detail', {
    methods: {
        getBundle() {
            this.bundle = this.repository.create(Shopware.Context.api);
        },
        onClickSave() {
            this.isLoading = true;

            this.repository.save(this.bundle, Shopware.Context.api).then(() => {
                this.isLoading = false;
                this.$router.push({ name: 'swag.bundle.detail', params: { id: this.bundle.id }});
            }).cath((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: 'Error',
                    message: exception
                })
            });
        }
    }
});
