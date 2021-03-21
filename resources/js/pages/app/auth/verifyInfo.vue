<template>
    <div id="verify-info" class="container verify-info pt-5">
        <div class="text-center pt-5">
            <div v-if="alert" class="alert alert-success" role="alert">
                メールの送信が完了しました
            </div>
            <div class="text-center">
                <h2>登録ありがとうございます</h2>
                <p>ご入力いただいたメールアドレスに、本登録用URLをお送りいたしましたので、ご確認のうえ、会員登録を完了させてください。</p>
            </div>
            <div class="text-center">
                <button @click="resend"
                        class="btn btn-primary">メール再送信</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import User from "../../../models/User";

    export default {
        middleware: 'auth',

        data() {
            return {
                loading: false,
                alert: false
            }
        },

        computed: {
        },

        methods: {
            async resend() {
                this.alert = false
                await this.$root.setPageLoading(async () => {
                    await auth.user().post('/api/email/resend')
                    this.alert = true
                },(e) => {
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .verify-info {
        height: calc(100vh - 270px);
    }
</style>
