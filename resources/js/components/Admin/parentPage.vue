<template>
    <div>

        <template v-if="isParentPage">
            <slot></slot>
        </template>

        <template v-if="!isParentPage">
            <transition name="page" mode="out-in">
                <router-view :key="$route.fullPath"></router-view>
            </transition>
        </template>
    </div>
</template>

<script>

    export default {
        name: 'parent-page',

        computed: {
            isParentPage() {
                if(this.$route.matched.length > 1) {
                    return false
                }
                return true
            }
        }

    }
</script>
