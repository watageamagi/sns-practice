<template>
    <div class="guest-layout">
        <transition name='fade' mode="out-in">
            <router-view :key="$route.fullPath"></router-view>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'guest-layout',

        created () {
            console.log('guest')
        }
    }
</script>

<style lang="scss" scoped>
</style>
