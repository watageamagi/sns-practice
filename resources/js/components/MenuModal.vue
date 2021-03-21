<template>
    <div>
        <transition name="fade">
            <div v-if="state" class="hamburger-menu">
                <div @click="close" class="overlay"></div>
                <ul v-if="state">
                    <template v-for="menu in menus">
                        <router-link v-if="menu.url" :to="menu.url"><li @click="close">{{menu.name}}</li></router-link>
                        <a v-if="menu.exUrl" :href="menu.exUrl" :target="menu.target"><li @click="close">{{menu.name}}</li></a>
                    </template>
                    <li @click.prevent="logout" v-if="isAuth()">ログアウト</li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import { action, getter } from '~/store/types'
    import HeaderMenu from "../const/app/HeaderMenu"
    export default {
        name: 'menu-modal',

        data() {
            return {
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
            close() {
                this[action.UPDATE_MENU_MODAL_STATUS](false)
            },
            async logout () {
                await this.$root.setPageLoading(async () => {
                    await auth.logout()
                    this.close()
                })
            },

            isAuth() {
                return auth.check()
            },
        }
    }
</script>

<style lang="scss" >
    @import "#/_mixin.scss";
    .hamburger-menu {
        position: fixed;
        z-index: 9999;
        width: 100%;
        height: 100%;
        top: 60px;
        left: 0;

        .overlay {
            background-color: rgb(0,0,0,0.7);
            width: 100%;
            height: 100%;
        }

        ul {
            position: absolute;
            width: 100%;
            top: 0;
            background-color: #fff;
            padding-left: 0;
            li {
                position: relative;
                height: 43px;
                border-bottom: 1px solid #c8c8c8;
                padding: 0 30px;
                display: flex;
                align-items: center;
                font-size: 15px;
                line-height: 29px;
                color: #505050;

                @include mq(sm) {
                    padding: 0 15px;
                }
            }
        }
    }
</style>

