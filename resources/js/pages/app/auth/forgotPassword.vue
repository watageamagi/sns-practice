<template>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <div class="card mt-4">
                <div class="card-header">
                    <p class="mb-0">パスワード再設定</p>
                </div>
                <div class="card-body">
                    <p>ご登録のメールアドレスを入力してください。パスワード再登録URLを発行します。</p>
                    <b-alert variant="success"　v-model="showSuccessAlert">
                        {{form.email}}にメールを送信しました。メールに記載しているURLからパスワードを再設定してください。</b-alert>
                    <form @submit.prevent="ReSendVerifyEmail">
                        <div class="form-group">
                            <label>Email</label>
                            <b-form-input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.has('email') }"
                                placeholder="Email"/>
                            <has-error :form="form" field="email"/>
                        </div>
                        <div class="form-group">
                            <b-button variant="primary" type="submit">送信</b-button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios'
    import { HasError, Form } from 'vform'


    export default {
        name: 'forgot-password',
        data() {
            return {
                form: new Form({
                    email: '',
                }),
                showSuccessAlert: false

            }
        },
        methods: {
            async ReSendVerifyEmail(){
                await this.$root.setPageLoading(async () => {
                    await this.form.post('/password/email')
                        .then(data => {
                            this.showSuccessAlert = true;
                        })
                })
            }
        },
        components: {
            HasError
        }

    }
</script>
