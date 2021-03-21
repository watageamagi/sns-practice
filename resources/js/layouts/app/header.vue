<template>
    <nav id="app_header" class="main-header navbar navbar-expand navbar-white border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <router-link to="/">
                    <img src="/images/logo.png"/>
                </router-link>
            </li>
        </ul>

        <!-- Right navbar links -->
        <b-navbar-nav class="ml-auto sp-none">
            <b-nav-item
                v-for="(menu, i) in menus"
                :href="menu.exUrl"
                :to="menu.url"
                :target="menu.target"
                :key="'nav_'+i">
                {{ menu.name }}
            </b-nav-item>
            <b-nav-item @click.prevent="logout" v-if="isAuth()">ログアウト</b-nav-item>
        </b-navbar-nav>

        <div class="ml-auto hamburgerBox pc-none-flex" @click="clickMenu()">
            <div id="hamburger" class="btn-trigger" :class="{'active': state}">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import { action, getter } from '~/store/types'
    import HeaderMenu from "../../const/app/HeaderMenu"

    export default {
        name: 'app-header',

        data(){
            return{
                appName: process.env.MIX_APP_NAME,
                menus: HeaderMenu.menus
            }
        },

        computed: {
            ...mapGetters({
                state: getter.MENU_MODAL,
            }),
        },

        methods: {
            ...mapActions([
                action.UPDATE_MENU_MODAL_STATUS,
            ]),

            async logout () {
                await this.$root.setPageLoading(async () => {
                    const res = await auth.setOutUrl('api/logout').logout()
                    // await res.logout()
                    // await auth.logout()
                })
            },

            isAuth() {
                return auth.check()
            },

            clickMenu() {
                this[action.UPDATE_MENU_MODAL_STATUS](!this.state)
            }
        }
    }

</script>

<style lang="scss" scoped>
    @import "#/_mixin.scss";
    #app_header {
        /*background-color: white;*/
        @include mq(md) {
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 99;
            width: 100%;
        }

        .nav-item {
            img {
                height: 40px;
            }
            @include mq(md) {
                img {
                    height: 30px;
                }
            }
        }

        .hamburgerBox {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 60px;
            padding: 0 15px;
        }

        // ハンバーガー
        .btn-trigger {
            position: relative;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .btn-trigger span {
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--dark);
            border-radius: 4px;
        }
        .btn-trigger, .btn-trigger span {
            display: inline-block;
            transition: all .5s;
            box-sizing: border-box;
        }
        .btn-trigger span:nth-of-type(1) {
            top: 0;
        }
        .btn-trigger span:nth-of-type(2) {
            top: 8px;
        }
        .btn-trigger span:nth-of-type(3) {
            bottom: 0;
        }
        #hamburger.active span:nth-of-type(1) {
            -webkit-transform: translateY(20px) rotate(45deg);
            transform: translateY(20px) rotate(45deg);
            top: -10px;
            width: 100%;
        }
        #hamburger.active span:nth-of-type(2) {
            left: 60%;
            opacity: 0;
            -webkit-animation: active-btn17-bar02 .8s forwards;
            animation: active-btn17-bar02 .8s forwards;
        }
        @-webkit-keyframes active-btn17-bar02 {
            100% {
                height: 0;
            }
        }
        @keyframes active-btn17-bar02 {
            100% {
                height: 0;
            }
        }
        #hamburger.active span:nth-of-type(3) {
            -webkit-transform: translateY(-20px) rotate(-45deg);
            transform: translateY(-20px) rotate(-45deg);
            bottom: -13px;
            width: 100%;
        }
    }
</style>
