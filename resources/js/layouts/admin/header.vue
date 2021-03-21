<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="user user-menu">
                <a @click.prevent="logout" href="#" class="mr-3">
                    <span>ログアウト</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'admin-header',

        data(){
            return{
                appName: process.env.MIX_APP_NAME
            }
        },

        methods: {
            async logout () {
                auth.logout(false)
            }
        }
    }

</script>
