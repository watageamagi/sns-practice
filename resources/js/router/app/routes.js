function page (path) {
    return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default ({ authGuard, guestGuard}) => [

        {
            path: '/login',
            name: 'login',
            component: page('app/auth/login.vue')
        },

        {
            path: '/register',
            name: 'register',
            component: page('app/auth/register.vue')
        },

        {
            path: '/email/verify/:id/:hash',
            name: 'verify-email',
            component: page('app/auth/verifyEmail.vue')
        },

        {
            path: '/password/forgot',
            name: 'forgot-password',
            component: page('app/auth/forgotPassword.vue')
        },

        {
            path: '/password/reset',
            name: 'password-reset',
            component: page('app/auth/passwordReset.vue')
        },

        {
            path: '/email/verify-info',
            name: 'verify-info',
            component: page('app/auth/verifyInfo.vue')
        },

        {
            path: '/',
            name: 'top',
            component: page('app/top.vue'),
            meta: {

            }
        },

        {
            path: '/top',
            redirect: '/'
        },
    { path: '*', redirect: '/' },
]
