<template>
    <div class="modal fade" id='modalCsvUpload' tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Upload CSV</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <div>
                            <label for="csv">Upload your CSV Doc</label>
                            <input type="file" name="csv" id="csv"
                                   @change="onFileChange">
                            <p class="help-block">CSV ...</p>
                            <button class="btn btn-default btn-md" @click="upload">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const file = "";
    export default {
        data() {
            return [file];
        },
        methods: {
            onFileChange: function (e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.$data.file = files[0];
            },
            upload: function (e) {
                event.preventDefault();
                const data = new FormData();
                data.append('csv', this.$data.file);
                axios.post('/api/products/upload-csv', data).then((response) => {
                    this.$parent.$bus.$emit('csvUploaded', response.data);
                }).catch(function (response, status, request) {
                    this.$parent.$bus.$emit('csvUploaded', response.data);
                });
            }
        }
    }
</script>