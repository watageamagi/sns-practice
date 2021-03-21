<template>
    <div class="container pt-5"
    style="background-color: white; height: 100vh;">
        <div class="text-center mt-5">
            <div
                    class="mt-2 alert"
                    role="alert"
                    :class="klass">
                {{msg}}
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import store from '../../../store'
    import Cookies from 'js-cookie'
    import User from "../../../models/User";

    export default {

        data() {
            return {
                msg: 'メール認証中',
                klass: 'alert-warning'
            }
        },

        computed: {
        },

        async mounted() {

            const id = this.$route.params.id
            const hash = this.$route.params.hash
            const query = this.$route.query

            if(!auth.check()) {
                this.msg = 'ログインしてからURLにアクセスしてください'
                return
            }

            const client = axios.create({
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.$route.query.token}`
                },
            })

            console.log(client)
            await client.get(`/api/email/verify/${id}/${hash}`)


            try {
                const { data } = await client.get(`/api/email/verify/${id}/${hash}`)
                await auth.setUser(new User(data))
                auth.setCookie('oauth_token', this.$route.query.token)
                this.msg = 'メール認証完了'
                this.klass = 'alert-success'

                this.$router.push({ name: 'top' })
            } catch (e) {
                this.msg = '認証に失敗しました。[code: E001]'
            }

        },

        methods: {
        }
    }
</script>
