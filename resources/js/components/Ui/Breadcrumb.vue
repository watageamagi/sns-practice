<template>
    <div>
        <b-breadcrumb class='breadcrumb-area' :items="breadcrumbs">
        </b-breadcrumb>
    </div>
</template>

<script>

    export default {
        name: 'breadcrumb',

        computed: {
            breadcrumbs() {
                // パンくずの定義がvue-routerのmetaフィールドでされているか調べる
                if( this.$route.matched.some( obj => obj.meta.breadcrumbs ) ){
                    return this.$route.meta.breadcrumbs
                }
                return
            }
        }


    }
</script>


<style lang="scss" scoped>
    .breadcrumb-area {
        background-color: transparent;
    }
</style>
