<template>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <b-card class="mt-4" header="パスワード再設定">
                <p>新しいパスワードを入力し、保存ボタンを押してください。</p>
                <form @submit.prevent="reset">
                    <alert-success :form="form" message="パスワードの変更が完了しました"/>

                    <alert-error v-if="form.errors.has('token')" :form="form" message="URLが正しくありませんもう一度メールを送信しなおしてください" />

                    <!-- Email -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">email</label>
                        <div class="col-md-7">
                            <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="メールアドレス" readonly>
                            <has-error :form="form" field="email"/>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">パスワード</label>
                        <div class="col-md-7">
                            <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
                            <has-error :form="form" field="password"/>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right">パスワード再入力</label>
                        <div class="col-md-7">
                            <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation">
                            <has-error :form="form" field="password_confirmation"/>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group row">
                        <div class="col-md-9 ml-md-auto">
                            <b-button variant="primary" type="submit">
                                パスワード再設定
                            </b-button>
                        </div>
                    </div>
                </form>
            </b-card>
        </div>
    </div>
</template>

<script>
    import {Form} from 'vform'

    export default {

        name: 'password-reset',

        data: () => ({
            status: '',
            form: new Form({
                token: '',
                email: '',
                password: '',
                password_confirmation: ''
            })
        }),

        created () {
            this.form.email = this.$route.query.email
            this.form.token = this.$route.query.token
        },

        methods: {
            async reset () {
                await this.$root.setPageLoading(async () => {
                    const { data } = await this.form.post('/password/reset')

                    this.status = data.status

                    this.form.reset()
                })
            }
        }
    }
</script>
