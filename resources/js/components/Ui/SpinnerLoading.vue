<template>
    <div :class="align" v-if="loading">
        <div class="overlay"></div>
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'spinner-loading',

        data: () => ({
        }),

        props: {
            loading: false,
            align: {
                type: String,
                default: 'text-center'
            }
        },

        mounted () {
        }
    }

</script>

<style lang="scss" scoped>
    @import "#/_variables.scss";

    .page-loading {
        position: fixed;
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1052;
        top: 0;

        .spinner-border {
            color: var(--dark);
        }

        .overlay {
            background: #fff;
            opacity: 0.5;
            width: 100%;
            height: 100vh;
            position: absolute;
        }
    }

</style>
