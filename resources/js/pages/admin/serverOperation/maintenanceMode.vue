<template>
    <div id="maintenance-mode">

        <section class="content-header">
            <h1>メンテナンスモード操作</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">現在のステータス</h3>
                        </div>

                        <div class="card-body d-flex align-items-center">
                            <div>
                                <h2 class="text-danger" v-if="isMaintenance">現在メンテナンスモード</h2>
                                <h2 class="text-success" v-else>現在通常モード</h2>
                            </div>
                            <div class="ml-auto">
                                <button class="btn btn-danger" v-if="!isMaintenance" @click="changeMaintenance">メンテナンスモードに切り替える</button>
                                <button class="btn btn-success" v-else @click="changeMaintenance">通常モードに切り替える</button>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title font-weight-bold">メンテナンスモード時にアクセス可能なIPアドレス一覧</h3>
                            <button class="btn btn-danger ml-auto" @click="showAddModal">IPアドレス追加</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>IP</th>
                                    <th>説明</th>
                                    <th style="width: 60px;">削除</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in ipLists">
                                    <td>{{item.id}}</td>
                                    <td>{{item.ip}}</td>
                                    <td>{{item.description}}</td>
                                    <td>
                                        <button type="button"
                                                @click="deleteIp(item)"
                                                class="btn btn-block btn-outline-danger btn-sm">
                                            <font-awesome-icon icon="times"/>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </section>

        <b-modal id="maintenanceIp-create-modal"
                 title="IPアドレス追加"
                 @ok="addIP"
                 body-class="modal-size">
            <div class="card-body">
                <div class="form-group">
                    <label>IP アドレス</label>
                    <input class="form-control" v-model="model.ip" placeholder="000.000.000">
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <input class="form-control" v-model="model.description" placeholder="説明">
                </div>
            </div>
        </b-modal>

    </div>
</template>

<script>

    import { mapActions, mapGetters } from 'vuex'
    import MaintenanceAccess from "../../../models/Entities/MaintenanceAccess"
    import axios from 'axios'
    import linq from 'linq'

    export default {
        name: 'maintenance-mode',
        middleware: 'admin',

        data () {
            return {
                model: new MaintenanceAccess(),
                isMaintenance: false,
                lists: []
            }
        },

        computed: {
            ipLists() {
                return this.lists
            }
        },

        created () {
            this.init()
        },

        methods: {
            ...mapActions([
            ]),

            init() {
                this.$root.setPageLoading(async() => {
                    await this.fetch()
                })
            },

            async fetch() {
                const { data } = await axios.get(`/api/admin/maintenance`)
                this.isMaintenance = data.isMaintenance
                this.lists = linq.from(data.ipList).select(x => new MaintenanceAccess(x)).toArray()
            },

            changeMaintenance() {
                const message = this.isMaintenance ? 'メンテナンスモードを解除しますか？' : 'メンテナンスモードをにしますか？'
                this.$confirmModal(message, (res) => {
                    if(!res) return

                    this.$root.setPageLoading(async() => {
                        const { data } = await axios.post(`/api/admin/maintenance/change`, {
                            'isMaintenance': this.isMaintenance
                        })
                        this.isMaintenance = data.isMaintenance
                        this.$alertModal(data.message)
                    }, (e) => {
                        this.$errorModal(e)
                    })
                })
            },

            showAddModal() {
                this.model = new MaintenanceAccess()
                this.$openModal('maintenanceIp-create-modal')
            },

            addIP() {
                this.$root.setPageLoading(async() => {
                    const { data } = await this.model.post('/api/admin/maintenance')
                    this.fetch()
                    this.$alertModal(data.message)
                }, (e) => {
                    this.$errorModal(e)
                })
            },

            deleteIp(model) {
                this.$confirmModal(model.ip+'アドレスを削除しますか？', (res) => {
                    if(!res) return

                    this.$root.setPageLoading(async() => {
                        const { data } = await axios.delete(`/api/admin/maintenance/${model.id}`)
                        this.fetch()
                        this.$alertModal(data.message)
                    }, (e) => {
                        this.$errorModal(e)
                    })
                })
            }

        },

        components: {
        },
    }

</script>


<style lang="scss" scoped>
    #home-list {
    }
</style>
