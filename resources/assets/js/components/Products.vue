<template>
    <div class="table-responsive">
        <button class="btn btn-primary" @click="add()">Add Product</button>
        <button class="btn btn-primary" @click="importCsv()">Import CSV</button>
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
            this.$parent.$bus.$on('close', reload => {
                if (reload) this.getList();
            });
            this.$parent.$bus.$on('csvUploaded', response => {
                $('#modalCsvUpload').modal('toggle');
                this.$parent.$bus.$emit('emitMessage', {
                    'class': response.status !== 200 ? 'danger' : 'success',
                    'message': response.message + ' successfully exported!'
                });
            });
        },
        methods: {
            importCsv() {
                $('#modalCsvUpload').modal();
            },
            add() {
                this.$parent.$bus.$emit('saveProduct');
                $('#modalForm').modal();
            },
            edit(id) {
                axios.get('api/products/find/' + id).then(response => {
                    this.$parent.$bus.$emit('editProduct', response.data);
                    $('#modalForm').modal();
                });
            },
            remove(id) {
                const confirmation = window.confirm('Are you sure?');
                if (!confirmation) return;
                axios.delete('api/products/destroy/' + id).then(response => {
                    this.$parent.$bus.$emit('emitMessage', {
                        'class': 'success',
                        'message': response.data.message
                    });
                    this.getList();
                });
            },
            getList() {
                axios.get('api/products').then(response => this.products = response.data);
            }
        }
    }
</script>

