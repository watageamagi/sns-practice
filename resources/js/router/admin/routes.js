
function page (path) {
    return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default ({ authGuard, guestGuard}) => [

    // Authenticated routes.
    ...guestGuard([
        {
            path: '/admin/login',
            name: 'login',
            component: () => import(
                /* webpackChunkName: "login" */
                '~/pages/admin/auth/login.vue')
                .then(m => m.default || m),
        },
    ]),
    // Guest routes.
    ...authGuard([
        {
            path: '/admin',
            redirect: '/admin/dashboard'
        },

        {
            path: '/admin/dashboard',
            name: 'dashboard',
            meta: {
                title: 'ダッシュボード',
            },
            component: page('admin/dashboard.vue')
        },

        {
            path: '/admin/users',
            name: 'user-list',
            meta: {
                title: 'ユーザーリスト',
            },
            component: page('admin/user/user-list.vue'),
            children: [
                {
                    path: 'detail/:id?',
                    name: 'user-detail',
                    meta: {
                        title: "ユーザー詳細",
                        back: true
                    },
                    component: page('admin/user/user-detail.vue')
                },
            ]
        },

        {
            path: '/admin/server-operation/maintenance-mode',
            component: page('admin/serverOperation/maintenanceMode.vue')
        },

        { path: '*', component: page('admin/errors/404.vue') },
    ]),
]
