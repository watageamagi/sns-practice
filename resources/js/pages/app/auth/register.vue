<template>
    <b-container>
        <div class="col-md-6 offset-md-3">
            <b-card class="w-100 mt-4" header="会員登録">
                <section>
                    <div class="mb-4 register-form">
                        <label class="register-card_label">名前</label>
                        <b-form-input
                            id="name"
                            :class="{ 'is-invalid': user.form.errors.has('name') }"
                            class="register-form_input"
                            v-model="user.name" />
                        <has-error field="name" :form="user.form"></has-error>
                    </div>

                    <div class="mb-4 register-form">
                        <label class="register-card_label">ID（メールアドレス）</label>
                        <b-form-input
                            id="email"
                            placeholder="example@frenze-singapole.com"
                            :class="{ 'is-invalid': user.form.errors.has('email') }"
                            class="register-form_input"
                            v-model="user.email" />
                        <has-error field="email" :form="user.form"></has-error>
                    </div>

                    <div class="mb-4 register-form">
                        <label class="register-card_label">パスワード設定</label>
                        <b-form-input
                            id="password"
                            placeholder="*********"
                            :class="{ 'is-invalid': user.form.errors.has('password') }"
                            class="register-form_input"
                            type="password"
                            v-model="user.password" />
                        <has-error field="password" :form="user.form"></has-error>
                    </div>

                    <div class="register-form">
                        <div class="text-secondary">※全ての同意事項を確認した後、登録を押してください。</div>
                    </div>
                </section>


                <div class="d-flex justify-content-center mt-4 mb-4">
                    <b-button variant="primary" @click="register()">登録する</b-button>
                </div>
            </b-card>
        </div>
    </b-container>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import { action, getter } from '~/store/types'
    import * as FU from '../../../utilities/fileUtility'

    import User from '../../../models/User'
    import OAuthRequest from "../../../models/Entities/OAuthRequest";
    import OAuthResult from "../../../models/Entities/OAuthResult";

    export default {
        name: 'register-page',
        middleware: 'guest',

        data() {
            return {
                completed: false,
                registerCheck: false,
                isTerm: false,
                user: new User(),
            }
        },

        created() {
        },

        computed: {
            ...mapGetters({
            }),
        },

        methods: {
            ...mapActions([
            ]),

            hide() {
            },

            async register() {
                await this.$root.setPageLoading(async () => {
                    await auth.OAuthRegister(this.user,
                        () => {
                            this.$router.push({ name: 'verify-info' })
                        },
                        (e) => {
                            throw new Error(e)
                            return
                    })
                })
            },
        },

        components: {
        }

    }
</script>

<style lang="scss" scoped>
    @import '#/_mixin.scss';


</style>


