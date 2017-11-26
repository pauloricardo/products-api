<template>
    <div class="modal fade" id='modalAddProduct' tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Product</h4>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" v-model="product.name" name="name" id="name"
                                   placeholder="Goku God T-Shirt">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" v-model="product.description" name="description"
                                      id="description"
                                      placeholder="type your text"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image of your product</label>
                            <input type="file" name="image" id="image" @change="upload">
                            <p class="help-block">Ka me ha me ha ...</p>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" name="price" v-model="product.price" class="form-control" id="price"
                                   placeholder="price">
                        </div>
                        <div class="form-group">
                            <label for="product_categories_id">Product Category</label>
                            <select class="form-control" name="product_categories_id" id="product_categories_id"
                                    @input="handleSelect($event)">
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
        product_categories_id: ''
//        csrf_token: Laravel.csrfToken
    };
    export default {
        beforeCreate() {
            axios.get('api/api/product-categories').then(response => this.categories = response.data);
        },
        data() {
            return {product, categories: []};
        },
        methods: {
            upload: function (event) {
                event.preventDefault();
                const files = event.target.files;
                const data = new FormData();
                data.append('image', files[0]);

                axios.post('upload-image', data).then((response) => {
                    if (response.status !== 200) return;
                    this.$data.product.image = response.data.file;

                }).catch(function (response, status, request) {
                    console.log(response, status, request);
                });
            },
            submit: function (event) {
                axios.post('/api/api/products/create', this.$data.product).then((response) => {
                    alert(response.data.message);
                    this.$parent.$emit('reloadList', true);
                }).catch(error => {
                    this.catchErrors(error);
                });
            },
            catchErrors: function (errorObject) {
                switch (errorObject.request.status) {
                    case 422:
                        let errors = '';
                        for (let prop in errorObject.response.data.errors) {
                            if (prop !== undefined && errorObject.response.data.errors[prop] !== undefined) {
                                errors += prop + ': ' + errorObject.response.data.errors[prop] + '\n';
                            }
                        }
                        alert(errors);
                        break;
                    default:
                        alert(response.data.message);
                }
            },
            handleSelect(teste) {
                this.$data.product.product_categories_id = teste.target.value;
            }
        }
    }

</script>

