<template>
    <a-lte-aside mini>
        <router-link to="/admin" class="brand-link text-center mb-3" >
                <span class="logo-lg"><b>{{ adminName }}</b>ADMIN</span>
        </router-link>

        <a-lte-aside-container>

            <a-lte-aside-nav>
                <template v-for="item in menu">
                    <a-lte-aside-nav-header v-if="!item.children && !item.path"
                                            :title="item.title"
                                            :key="item.title" />
                    <a-lte-side-nav-item v-if="!item.children && item.path"
                                         :title="item.title"
                                         :key="item.title"
                                         :icon="item.icon"
                                         :icon-color="item.iconColor"
                                         :badge="item.badge"
                                         :query="item.query"
                                         :path="item.path" />

                    <AdminLteSideNaviItemDropdown v-if="item.children"
                                                  :key="item.title"
                                                  :title="item.title"
                                                  icon-expand="angle-down"
                                                  :icon-color="item.iconColor"
                                                  :icon="item.icon">
                        <a-lte-side-nav-item v-for="submenu in item.children"
                                             class="ml-4 f-14"
                                             :key="submenu.title"
                                             :title="submenu.title"
                                             :icon="submenu.icon"
                                             :path="submenu.path"
                                             :query="submenu.query"
                                             :badge="submenu.badge"/>
                    </AdminLteSideNaviItemDropdown>

                </template>
            </a-lte-aside-nav>
        </a-lte-aside-container>
    </a-lte-aside>
</template>

<script>
    import AdminLteSideNaviItemDropdown from "../../components/admin/adminLte3/AdminLteSideNaviItemDropdown"
    import ALteSideNavItem from "../../components/admin/adminLte3/AdminLteSideNaviItem"
    import AdminSideMenu from "../../const/admin/SideMenu"

    export default {
        name: 'admin-sidebar',
        data () {
            return {
                // appName: process.env.MIX_APP_NAME,
                appName: 'Laravel',
                menu: AdminSideMenu.menu,
                adminName: (process.env.MIX_ADMIN_NAME ? process.env.MIX_ADMIN_NAME : 'laravel')

            }
        },

        components: {
            ALteSideNavItem,
            AdminLteSideNaviItemDropdown
        }
    }
</script>

<style lang="scss" scoped>
</style>
