import template from './import-product-index.html.twig';


const { Component, Mixin } = Shopware;

Component.register('import-product-index', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            file: null,
            isLoading: false,
            validationResult: null,
            errors: null,
            repository: null,
            bundles: null,
            token: null,
            id: null,
        }
    },
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    methods: {
        onFileSelected(file) {
            console.log('CLICKEC');
            this.file = file;
        },
        async validateFile() {
            if (!this.file) {
                console.log('no FILE');
                return;
            }

            this.isLoading = true;

            const formData = new FormData();
            formData.append('file', this.file);

            try {
                const token = await this.getToken();

                const response = await fetch('/api/product-import/validate', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Authorization': `Bearer ${token}`, // Add OAuth token here
                    },
                });

                this.validationResult = await response.json();

                if (this.validationResult.valid) {
                    console.log('VALID');

                } else {
                    this.errors = this.validationResult.errors;
                    console.log(this.errors);
                    console.log('WARNING');

                }
            } catch (error) {
                console.log('ERORRR');
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },

        async startImport() {
            if (!this.file) {
                return;
            }

            this.isLoading = true;

            const formData = new FormData();
            formData.append('file', this.file);

            try {
                const token = await this.getToken();

                const response = await fetch('/api/product-import/import', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Authorization': `Bearer ${token}`, // Add OAuth token here
                    },
                });

                const result = await response.json();

                if (response.ok) {
                    this.createNotificationSuccess({
                        title: 'File upload success',
                        message: 'Your file is uploaded'
                    });
                    this.id = result.id;

                    this.$router.push({
                        name: 'import.product.detail',
                        params: {
                            id: this.id
                        }
                    });

                    // sw.window.routerPush();


                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                console.log('upload error');
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        },

        async getToken() {
            try {
                const response = await fetch('/api/oauth/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        client_id: 'SWIAMLLQAU9XVZRADDFNVE12YQ',
                        client_secret: 'aWhGVDUyNW1Gak5XVmQ0UDZzUGxONWNYWVlvaVBvSXh0aERGZTk',
                        grant_type: 'client_credentials'
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                this.token = data.access_token;

                return data.access_token;
            } catch (error) {
                console.log('GENERATE TOKEN');
                console.log(error);
            }
        }
    }
});
