<template>
    <div id="app-login" class="d-flex align-items-center">
        <b-container>
            <div class="col-md-6 offset-md-3">
                <b-card class="w-100 mt-4" header="ログイン">
                    <div v-if="oauth.hasError" class="alert alert-danger" role="alert">
                        <p class="mb-0">ログインに失敗しました。</p>
                        <p class="mb-0">メールアドレスまたはパスワードをご確認ください</p>
                    </div>
                    <form @submit.prevent="login" method="post">
                        <div class="mb-3">
                            <b-input-group>
                                <b-input-group-prepend
                                    is-text>
                                    <font-awesome-icon icon="user" />
                                </b-input-group-prepend>
                                <b-form-input
                                    class="register-card_input"
                                    type="email"
                                    id="email"
                                    v-model="oauth.username" placeholder="メールアドレス"/>
<!--                                <has-error field="email" :form="auth.form"></has-error>-->
                            </b-input-group>
                        </div>

                        <div class="mb-3">
                            <b-input-group>
                                <b-input-group-prepend
                                    is-text>
                                    <font-awesome-icon icon="lock" />
                                </b-input-group-prepend>
                                <b-form-input
                                    class="register-card_input"
                                    id="password"
                                    type="password"
                                    v-model="oauth.password" placeholder="パスワード"/>
<!--                                <has-error field="password" :form="auth.form"></has-error>-->
                            </b-input-group>
                        </div>

                        <div class="d-flex justify-content-center mt-5 mb-5">
                            <b-button variant="primary" type="submit">ログインする</b-button>
                        </div>
                    </form>
                    <div class="text-center">
                        <div @click="goRegister()" class="pointer text-register"><u>新規でアカウントを作成する</u></div>
                        <router-link to="/password/forgot"><small>パスワードを忘れた場合</small></router-link>
                    </div>
                </b-card>
            </div>
        </b-container>
    </div>
</template>

<script>
    import OAuthRequest from '../../../models/Entities/OAuthRequest'
    import axios from 'axios'

    export default {
        name: 'app-login',
        middleware: 'guest',

        data: () => ({
            oauth: new OAuthRequest(),
        }),

        computed: {
        },

        async created() {
        },

        methods: {

            async login () {
                await this.$root.setPageLoading(async () => {
                    console.log(this.oauth)
                    await auth.OAuthLogin(this.oauth)
                })
            },

            goRegister() {
                this.$router.push({ name: 'register' })
            },
        },

        components: {
        }
    }
</script>

<style lang="scss">
    @import '#/_mixin.scss';
    #app-login {

    }

    .text-register {
        color: #E4322C;
        font-size: 14px;
    }
</style>
