<template>
    <div class="wrapper">

        <!-- Navbar -->
        <admin-header/>

        <!-- Main Sidebar Container -->
        <admin-sidebar/>

        <a-lte-content>
            <b-container slot="header" fluid>
                <b-row class="mb-2">
                    <b-col sm="6">
                        <h1 class="m-0 text-dark">{{ title }}</h1>
                        <div class="back-link pl-0 mt-2 col-2 pointer"
                             v-if="isBackIcon"
                             @click="$router.go(-1)">
                            <font-awesome-icon icon="arrow-circle-left" /> 戻る
                        </div>
                    </b-col>
                    <b-col sm="6" class="d-flex justify-content-end">
                        <admin-breadcrumbs :list="breadcrumbs" transition="breadcrumbs" />
                    </b-col>
                </b-row>
            </b-container>

            <b-container fluid>
                <b-row>
                    <b-col>
                        <transition name="page" mode="out-in">
                            <router-view :key="$route.fullPath"></router-view>
                        </transition>
                    </b-col>
                </b-row>
            </b-container>
        </a-lte-content>

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2020</strong> All rights reserved.
        </footer>
    </div>
</template>

<script>

    import AdminHeader from './header';
    import AdminSidebar from './sidebar';
    import AdminBreadcrumbs from "../../components/admin/adminLte3/AdminBreadcrumbs"

    export default {
        name: 'app-layout',

        created () {
        },

        computed: {
            title() {
                if (this.$route.meta && this.$route.meta.title) {
                    return this.$route.meta.title;
                }
                if (this.$route.name) {
                    return this.$route.name;
                }
                return '';
            },
            breadcrumbs() {
                return this.$route.matched;
            },
            isBackIcon() {
                if (this.$route.meta && this.$route.meta.back) {
                    return this.$route.meta.back;
                }
                return false
            }
        },

        components: {
            AdminBreadcrumbs,
            AdminSidebar,
            AdminHeader
        },
    }
</script>

<style lang="scss" scoped>
</style>
