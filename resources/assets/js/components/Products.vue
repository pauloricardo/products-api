<template>
    <div class="table-responsive">
        <button class="btn btn-primary" @click="add()">Add Product</button>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Price</th>
                <th>Category</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products">
                <td>{{ product.id }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.description }}</td>
                <td>{{ product.image }}</td>
                <td>{{ product.price }}</td>
                <td>{{ product.product_categories.name }}</td>
                <td>
                    <div class='btn-control'>
                        <button class='btn btn-success btn-md' @click="edit(product.id)">
                            Edit
                        </button>
                        <button class='btn btn-danger btn-md' @click="remove(product.id)">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {products: []};
        },
        mounted() {
            this.getList();
            this.$parent.$on('reloadList', reload => {
                if (reload) this.getList();
            });
        },
        methods: {
            add() {
                $('#modalAddProduct').modal();
            },
            edit(id) {
                axios.get('product/' + id + '/find').then(response => {
                    this.$parent.$emit('product', response.data);
                    $('#modalEditProduct').modal();
                });
            },
            remove(id) {
                const confirmation = window.confirm('Are you sure?');
                if (!confirmation) return;
                axios.delete('product/' + id).then(response => {
                    alert(response.data.message);
                    this.getList();
                });
            },
            getList() {
                axios.get('api/api/products').then(response => this.products = response.data);
            }
        }
    }
</script>

