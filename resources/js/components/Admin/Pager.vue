<template>
    <div id="pager">

        <b-pagination
            :class="className"
            v-if="paginate.data.length"
            v-model="paginate.currentPage"
            :per-page="paginate.perPage"
            :total-rows="paginate.total"
            @change="go"/>

    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Paginate from '../../models/Paginate'

    export default {
        name: 'pager',

        data() {
            return {
                currentPage: 1,
            }
        },

        props: {
            paginate: {
                type: Paginate,
                default: new Paginate(),
            },
            className: {
                type: String,
                default: ''
            }
        },

        computed: {
          ...mapGetters({
          })
        },

        async created() {
        },

        methods: {
            ...mapActions([
            ]),

            go(page) {
                const url = this.paginate.getPath(page)
                this.$router.push({ path: `${url}` })
                this.$emit('click', url)
            }
        },

        components: {
        },
    }
</script>
<style lang="scss">
    #pager {
        .page-btn {
            height: 100%;
            width: 100%;
        }

        .page-item.disabled .page-link {
            cursor: auto;
            pointer-events: none;
            color: #8898aa;
            border-color: #dee2e6;
            background-color: #fff;
        }

        .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: .375rem;
            border-bottom-left-radius: .375rem;
        }

        .page-item .page-link, .page-item span {

        }

        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: .375rem;
        }

        .page-item.active .page-link {
            /*box-shadow: 0 7px 14px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);*/
        }

        .page-item.active .page-link {

        }

        .page-link {
        }
    }
</style>
