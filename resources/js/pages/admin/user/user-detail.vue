<template>
    <div id="user-detail">

        <div class="content">
            <div class="row col-md-12">
                <div class="col-md-8 mb-3">
                    <div class="card card-outline card-primary h-100">
                        <div class="card-body">
                            <div>
                                <small class="text-secondary">登録日 {{ user.displayCreatedAt }}</small>
                            </div>
                            <div>
                                <small class="text-secondary">ID {{ user.id }}</small>
                            </div>

                            <h4 class="text-center pt-3">
                                {{ user.name }}
                            </h4>

                            <div class="text-center pt-3">
                                <i class="far fa-envelope"/>
                                {{ user.email }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from '../../../models/User'
    import store from '../../../store'
    import axios from 'axios'
    import ImageModal from "../../../components/admin/ImageModal"

    export default {
        name: 'user-detail',
        components: { ImageModal},
        data() {
            return {
                user: new User(),
                judgeStatusList: [],
            }
        },

        async created() {
            await this.fetch()
        },

        methods: {

            async fetch() {
                const id = store.state.route.params.id

                await this.$root.setPageLoading(async () => {
                    const { data } = await axios.get(`/api/admin/user/${id}`)
                    this.user = new User(data.user)
                    this.judgeStatusList = data.judgeStatusList
                    await this.$nextTick()
                })
            },

            async send() {
                if (!confirm('ユーザーにメールが送信されます。よろしいですか？')) return

                await this.$root.setPageLoading(async () => {
                    const { data } = await this.user.patch(`/api/admin/user`)
                    this.user = new User(data)
                    await this.$nextTick()
                })
            }
        },
    }

</script>


<style lang="scss" scoped>
    #user-detail {
        table tbody tr {
            th {
                padding-left: 0;
                font-weight: normal;
                border: none;
            }

            td {
                border: none;
            }
        }
    }
</style>
