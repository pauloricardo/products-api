<template>
    <div class="modal fade" id='modalForm' tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{formTitle}}</h4>
                </div>
                <div class="modal-body">
                    <banner-message></banner-message>
                    <form enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" v-model="product.name" name="name" id="name"
                                   placeholder="Product name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" v-model="product.description" name="description"
                                      id="description"
                                      placeholder="type your text"></textarea>
                        </div>
                        <div class="form-group">
                            <div style="float: left">
                                <img :src="imgSrc" alt="Image" width="120" height="120">
                            </div>
                            <div style="position: relative; left: 10px; top: 15px;">
                            <label for="image">Image of your product</label>
                            <input type="file" name="image" id="image"
                                   @change="onFileChange">
                            <p class="help-block">Image ...</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" name="price" v-model="product.price" class="form-control" id="price"
                                   placeholder="price">
                        </div>
                        <div class="form-group">
                            <label for="product_categories_id">Product Category</label>
                            <select class="form-control" name="product_categories_id" id="product_categories_id"
                                    v-model="product.product_category_id">
                                <option>Product category</option>
                                <option v-for="category of categories" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <button type="button" @click="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const product = {
        name: '',
        description: '',
        image: '',
        price: '',
        product_category_id: '',
//        csrf_token: Laravel.csrfToken
    };
    let formTitle, successMessage, submitPath, imgSrc = '';
    export default {
        beforeCreate() {
            axios.get('api/product-categories').then(response => this.categories = response.data);
        },
        data() {
            return {product, categories: [], formTitle, successMessage, submitPath, imgSrc};
        },
        mounted() {
            this.$parent.$bus.$on('saveProduct', () => {
                this.resetProductObject();
                this.$data.formTitle = 'Add Product';
                this.$data.submitPath = '/api/products/create';
                this.$data.imgSrc = '';

            });
            this.$parent.$bus.$on('editProduct', (product) => {
                this.$data.product = product;
                this.$data.formTitle = 'Update Product';
                this.$data.submitPath = '/api/products/edit/' + product.id;
                this.$data.imgSrc = 'products/images/' + this.$data.product.image;
            })
        },
        methods: {
            resetProductObject() {
                this.$data.product.name = '';
                this.$data.product.description = '';
                this.$data.product.image = '';
                this.$data.product.price = '';
                this.$data.product.product_categories_id = '';
            },
            onFileChange: function(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.$data.product.img = files[0];
                this.upload(e);
            },
            upload: function(e){
                event.preventDefault();
                const data = new FormData();
                data.append('image', this.$data.product.img);

                axios.post('/api/api/products/upload-image', data).then((response) => {
                    this.$data.product.image = response.data.message;
                    this.$data.imgSrc = 'products/images/' + this.$data.product.image;
                }).catch(error => {
                    this.$data.imgSrc = '';
                    this.$data.product.image = '';
                    this.$parent.$bus.$emit('emitMessage', {
                        'class': 'danger',
                        'message': error.response.data.message
                    });
                });
            },
            submit: function(event) {

                axios.post(this.submitPath, this.$data.product).then((response) => {
                    this.$parent.$bus.$emit('close', true);
                    this.$parent.$bus.$emit('emitMessage', {
                        'class': 'success',
                        'message': response.data.message
                    });
                    $('#modalForm').modal('toggle');
                }).catch(error => {
                    this.catchErrors(error);
                });
            },
            catchErrors: function (errorObject) {
                let errors = '';
                switch (errorObject.response.status) {
                    case 422:
                        for (let prop in errorObject.response.data.errors) {
                            if (prop !== undefined && errorObject.response.data.errors[prop] !== undefined) {
                                errors += prop + ': ' + errorObject.response.data.errors[prop] + '\r\t';
                            }
                        }
                        break;
                    default:
                        errors = response.data.message;
                }
                this.$parent.$bus.$emit('emitMessage', {
                    'class': 'danger',
                    'message': errors
                });
            },
            handleSelect(teste) {
                console.log(this.$data.product);
                this.$data.product.product_categories_id = teste.target.value;
            }
        }
    }

</script>

