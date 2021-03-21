<template>
    <div class="app-layout" v-cloak>

        <!-- Navbar -->
        <app-header/>

        <div class="content-wrapper position-relative">
            <transition name='fade' mode="out-in">
                <router-view :key="$route.fullPath"></router-view>
            </transition>

            <scroll-top-btn></scroll-top-btn>
        </div>

        <app-footer/>

        <menu-modal/>
    </div>
</template>

<script>

    import AppHeader from './header';
    import ScrollTopBtn from "../../components/Ui/ScrollTopBtn"
    import AppFooter from './footer'
    import MenuModal from "../../components/MenuModal";

    export default {
        name: 'app-layout',

        created () {
        },

        components: {
            MenuModal,
            AppHeader,
            ScrollTopBtn,
            AppFooter,
        },
    }
</script>

<style lang="scss" scoped>
    @import "#/_mixin.scss";

    .content-wrapper {
        @include mq(md) {
            margin-top: 60px;
        }
    }
</style>
