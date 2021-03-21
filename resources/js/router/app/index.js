import Vue from 'vue'
import store from '~/store'
import routes from './routes'
import Router from 'vue-router'
import { sync } from 'vuex-router-sync'

Vue.use(Router)

// Load middleware modules dynamically.
const routeMiddleware = resolveMiddleware(
    require.context('~/middleware', false, /.*\.js$/)
)

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
        if (auth.isNeedLoadUser()) {
            await auth.fetch()
        }

        // Get the first matched component.
        const components = await resolveComponents(
            router.getMatchedComponents({ ...to })
        )

        if (components.length === 0) {
            return next()
        }
        await router.app.$nextTick()

        setLayout(router, from, to, components)

        await middlewareGuard(from, to, next, components)
    })

    // Register after hook.
    router.afterEach((to, from) => {
        router.app.$nextTick(() => {
            router.app.$loading.finish()
        })
    })

    return router
}

async function middlewareGuard (from, to, next, components) {

    // Get the middleware for all the matched components.
    const middleware = getMiddleware(components)

    // // Call each middleware.
    callMiddleware(middleware, to, from, (...args) => {
        next(...args)
    })
}

/**
 * Set the application layout from the matched page component.
 *
 * @param {Router} router
 * @param {Route} to
 */
async function setLayout (router, from, to, components) {

    // Start the page loading bar.
    if (components[0].loading !== false) {
        router.app.$loading.start()
    }

    // Set application layout.
    router.app.setLayout(components[0].layout || '')

}

function getMiddleware (components) {
    const middleware = []

    components.filter(c => c.middleware).forEach(component => {
        if (Array.isArray(component.middleware)) {
            middleware.push(...component.middleware)
        } else {
            middleware.push(component.middleware)
        }
    })

    return middleware
}

/**
 * Redirect to login if guest.
 *
 * @param  {Array} routes
 * @return {Array}
 */
function authGuard (routes) {
    return beforeEnter(routes, (to, from, next) => {
        const middleware = ['auth']
        // // Call each middleware.
        callMiddleware(middleware, to, from, (...args) => {
            next(...args)
        })
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
        const middleware = ['guest']
        // // Call each middleware.
        callMiddleware(middleware, to, from, (...args) => {
            next(...args)
        })
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

function callMiddleware (middleware, to, from, next) {
    const stack = middleware.reverse()

    const _next = async (...args) => {
        // Stop if "_next" was called with an argument or the stack is empty.
        if (args.length > 0 || stack.length === 0) {
            return next(...args)
        }

        const middleware = stack.pop()

        if (typeof middleware === 'function') {
            await middleware(to, from, _next)
        } else if (routeMiddleware[middleware]) {
            routeMiddleware[middleware](to, from, _next)
        } else {
            throw Error(`Undefined middleware [${middleware}]`)
        }
    }

    _next()
}

/**
 * @param  {Object} requireContext
 * @return {Object}
 */
function resolveMiddleware (requireContext) {
    return requireContext.keys()
        .map(file =>
            [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)]
        )
        .reduce((guards, [name, guard]) => (
            { ...guards, [name]: guard.default }
        ), {})
}
