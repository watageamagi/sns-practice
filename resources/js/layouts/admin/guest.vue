<template>
    <div class="app-layout">
        <div class="app-content">
            <router-view :key="$route.fullPath"></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'guest-layout',

        created () {
            console.log(this.$route.fullPath)
        }
    }
</script>

<style lang="scss" scoped>
</style>
