<template>
    <div id="user-list">

        <div class="content">

            <parent-page>
                <div class="col-12 p-0">
                    <user-search />
                </div>

                <div class="col-12 p-0">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">ユーザー一覧</h3>
<!--                            <button type="button" class="btn btn-sm btn-block btn-success w-auto mr-2">新規＋</button>-->
                        </div>
                        <div class="card-body table-responsive p-0 mb-0">
                            <table class="table table-striped table-sm text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>名前</th>
                                    <th>メールアドレス</th>
                                    <th>メール認証</th>
                                    <th>登録日</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="user in paginate.data" v-if="paginate.hasData">
                                    <td class="font-weight-bold">
                                        <router-link
                                            :to="{name: 'user-detail', params: {id: user.id}}">
                                        {{user.id}}
                                        </router-link>
                                    </td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ getMailVerify(user) }}</td>
                                    <td>{{ user.displayCreatedAt }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <pager class-name="mb-0 pagination-sm" :paginate="paginate"></pager>
                        </div>
                    </div>
                </div>
            </parent-page>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Pager from '../../../components/admin/Pager'
    import UserSearch from "../../../components/admin/user/UserSearch"
    import Paginate from '../../../models/Paginate'
    import User from '../../../models/User'
    import store from '../../../store'
    import { buildQuery } from '../../../utilities/stringUtility'
    import parentPage from "../../../components/admin/parentPage"

    export default {
        name: 'user-list',

        data () {
            return {
                paginate: new Paginate().setModel(User),
            }
        },

        async created () {
            if(this.$route.name == 'user-list') {
                this.fetch()
            }
        },

        computed: {

        },

        methods: {
            async fetch() {
                await this.$root.setPageLoading(async () => {
                    const query = Object.assign({}, store.state.route.query)
                    const {data} = await axios.get(`/api/admin/user/list?${buildQuery(query)}`)
                    this.paginate.create(data)
                    await this.$nextTick()
                })
            },

            // async goDetail(user) {
            //     this.$router.push({path: `/admin/users/detail/${user.id}`})
            // },

            getMailVerify(user){
                if( user.isVerify ){
                    return '認証'
                }else{
                    return '未認証'
                }
            },

        },



        components: {
            Pager,
            UserSearch,
            parentPage
        },
    }

</script>

<style lang="scss" scoped>
</style>
