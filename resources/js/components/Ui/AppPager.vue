<template>
    <div id="app-pager">

        <b-pagination
            v-if="paginate.data.length"
            v-model="paginate.currentPage"
            :per-page="paginate.perPage"
            :total-rows="paginate.total"
            next-class="move-btn"
            prev-class="move-btn"
            last-class="d-none"
            first-class="d-none"
            next-text=">"
            prev-text="<"
            @change="go"/>

    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Paginate from '../../models/Paginate'

    export default {
        name: 'app-pager',

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
                console.log("click!!!!")
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
    #app-pager {
        .page-item {
            padding: 0 8px;
        }

        .page-item .page-link {
            border-radius: 100%;
            color: #1b1e21;
            border-color: black;
        }

        .page-item.active .page-link {
            background-color: #E4322C;
            border-color: #E4322C;
            color: white;
        }

        .move-btn.page-item .page-link {
            border: 0;
            background-color: transparent;
        }
    }
</style>
