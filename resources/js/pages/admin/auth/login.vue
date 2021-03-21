<template>
    <div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>{{ adminName }}</b>
        </div>

        <div class="login-box-body">

            <p v-show="auth.hasError" class="text-danger">ログインに失敗しました</p>

            <form @submit.prevent="login">
                <div class="form-group has-feedback">
                    <input name="name" v-model="auth.name" type="text" class="form-control" placeholder="Name">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="password" v-model="auth.password" type="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-4">
                    </div><!-- /.col -->
                    <div class="col-4">
                        <button type="submit"
                                class="btn btn-primary btn-block btn-flat">ログイン</button>
                    </div><!-- /.col -->
                    <div class="col-4">
                    </div><!-- /.col -->
                </div>
            </form>
        </div>
    </div>
    </div>
</template>

<script>
    import AuthRequest from '../../../models/Entities/AuthRequest'

    export default {
        name: 'login',
        layout: 'guest',

        data () {
            return {
                auth: new AuthRequest(),
                // appName: process.env.MIX_APP_NAME,
                adminName: (process.env.MIX_ADMIN_NAME ? process.env.MIX_ADMIN_NAME : 'laravel')
            }
        },

        computed: {
            disabled () {
                return this.auth.name.length && this.auth.password.length
            },
        },

        methods: {
            async login () {
                await this.$root.setPageLoading(async () => {
                    await auth.setLoginUrl('login').login(this.auth)
                })
            },
        },
    }
</script>

<style lang="scss" scoped>
</style>
