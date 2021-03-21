import Vue from 'vue'
import store from '~/store'
// import Meta from 'vue-meta'
import routes from './routes'
import Router from 'vue-router'
import { sync } from 'vuex-router-sync'
import { action, getter } from '../../store/types'

// Vue.use(Meta)
Vue.use(Router)

const router = make(
    routes({ authGuard, guestGuard })
)

sync(store, router)

export default router

/**
 * Create a new router instance.
 *
 * @param  {Array} routes
 * @return {Router}
 */
function make (routes) {
    const router = new Router({
        routes,
        scrollBehavior,
        mode: 'history',
        linkActiveClass: 'active'
    })

    // Register before guard.
    router.beforeEach(async (to, from, next) => {
        // if (store.getters[getter.AUTH_CHECK] && !store.getters[getter.AUTH_USER].id) {
        //     await store.dispatch(action.AUTH_FETCH_USER)
        // }

        // if (auth.check() && !auth.user().id) {
        if (auth.isNeedLoadUser()) {
            await auth.fetch('/admin/user')
        }

        await setLayout(router, to)
        next()
    })

    // Register after hook.
    router.afterEach((to, from) => {
        router.app.$nextTick(() => {
            router.app.$loading.finish()
        })
    })

    return router
}

/**
 * Set the application layout from the matched page component.
 *
 * @param {Router} router
 * @param {Route} to
 */
async function setLayout (router, to) {
    // Get the first matched component.
    const components = await resolveComponents(
        router.getMatchedComponents({ ...to })
    )

    if (components.length === 0) {
        return
    }

    await router.app.$nextTick()

    // Start the page loading bar.
    if (components[0].loading !== false) {
        router.app.$loading.start()
    }

    // Set application layout.
    router.app.setLayout(components[0].layout || '')

}

/**
 * Redirect to login if guest.
 *
 * @param  {Array} routes
 * @return {Array}
 */
function authGuard (routes) {
    return beforeEnter(routes, (to, from, next) => {
        if (!auth.check()) {
            next({name: 'login'})
        } else {
            next()
        }
    })
}

/**
 * Redirect home if authenticated.
 *
 * @param  {Array} routes
 * @return {Array}
 */
function guestGuard (routes) {
    return beforeEnter(routes, (to, from, next) => {
        if (auth.check()) {
            next({name: 'dashboard'})
        } else {
            next()
        }
    })
}

/**
 * Apply beforeEnter guard to the routes.
 *
 * @param  {Array} routes
 * @param  {Function} beforeEnter
 * @return {Array}
 */
function beforeEnter (routes, beforeEnter) {
    return routes.map(route => {
        return { ...route, beforeEnter }
    })
}

/**
 * @param  {Route} to
 * @param  {Route} from
 * @param  {Object|undefined} savedPosition
 * @return {Object}
 */
function scrollBehavior (to, from, savedPosition) {
    return {x:0, y:0}
}

/**
 * Resolve async components.
 *
 * @param  {Array} components
 * @return {Array}
 */
async function resolveComponents(components) {
    return Promise.all(components.map(component => {
        return typeof component === 'function' ? component() : component
    }))
}
