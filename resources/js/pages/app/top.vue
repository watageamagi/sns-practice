<template>
    <div id="top">

        <div class="container pt-lg-3">

            <div class="row">
                TOP!!!
            </div>
        </div>
    </div>
</template>

<script>

    import { mapActions, mapGetters } from 'vuex'
    import {action, getter} from '../../store/types'

    export default {
        name: 'top',
        middleware: 'auth',

        data () {
            return {

            }
        },

        computed: {
            ...mapGetters({
            }),
        },

        async created () {
            await this.fetch()
        },

        mounted () {
        },

        methods: {
            ...mapActions([
            ]),

            fetch() {
                this.$root.setPageLoading(async () => {
                })
            },
        },

        beforeDestroy () {
        },

        components: {

        },

        directives: {
        }
    }

</script>


<style lang="scss" scoped>
    @import "#/_variables.scss";

    .side-menu {
        padding-top: 15px;
        width: 100%;

        .list-group-item {
            background-color: $body-bg;
            border: none;
            border-top: 1px solid #ddd;
        }
        .list-group-item.active {
             background-color: #E4322C;
        }
        .list-group-item.list-title {
            font-weight: bold;
            border-radius: 0;
            padding-bottom: 20px;
            border-top: 0;
        }

        .accordion-title.collapsed {
            .icon-down {
                transform: rotateX(0deg);
            }
        }
        .accordion-title {
            border-top: 1px solid #ddd!important;
            .icon-down {
                float: right;
                font-size: 20px;
                transform: rotateX(180deg);
            }
        }
    }


    .search-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }
</style>
