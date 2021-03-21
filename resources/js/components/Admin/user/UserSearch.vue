<template>
    <div id="user-search">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">検索</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form @submit.prevent>
                            <div class="form-group col-md-3">
                                <label>ユーザー名</label>
                                <input class="form-control" v-model="form.name" placeholder="ユーザー名"/>
                            </div>

                            <!-- <div class="form-group col-md-3">
                                <label>日時</label>

                                <div class="input-group date">

                                    <input type="number" v-model="form.dateStart"
                                        class="form-control">
                                    <span class="input-group-addon">～</span>

                                    <input type="number" v-model="form.dateEnd"
                                        class="form-control" >
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer clearfix">
                <button
                    class="btn-sm btn-primary"
                    :disabled="!search"
                    @click="search">
                    検索
                </button>

                <button type="button"
                        @click="clear"
                        class="btn-sm btn-danger btn-flat pull-right">クリア</button>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'vform'

    export default {
        name: 'user-search',

        props: {
        },

        data() {
            return {
                form: new Form({
                    paginate: this.$route.query.paginate || '',
                    type: this.$route.query.type || '',
                    name: this.$route.query.name || '',
                }),
            }
        },

        methods: {
            async search () {
                this.$router.push({query: this.form.data()})
            },

            async clear () {
                this.form.name = ''
                this.$router.push({query: this.form.data()})
            }
        },
    }

</script>

<style lang="scss" scoped>
</style>
