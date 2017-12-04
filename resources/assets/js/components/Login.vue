<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>

                    <div class="panel-body">
                        <form class="form-horizontal" novalidate>
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" v-model="login.email" required autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" v-model="login.password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="button" class="btn btn-primary" @click="submit">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const login = {
        email : '',
        password: ''
    };
    export default {
        data() {
            return {login};
        },
        mounted() {
        },
        methods: {
            submit: function(event) {
                axios.post('/api/login', this.$data.login).then((response) => {
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
            }
        }
    }

</script>