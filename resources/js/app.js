import Vue from 'vue'
import './plugins/app'
import store from './store'
import App from './pages/app'
import router from './router/app'
import BootStrapVue from 'bootstrap-vue'
import AuthService from './service/AuthService'
import VueScrollTo from 'vue-scrollto'
import { HasError, AlertError, AlertSuccess } from 'vform'
import modalUtility from './utilities/modalUtility'


Vue.use(VueScrollTo, {
    offset: -60,
})

Vue.use(BootStrapVue)
Vue.mixin(modalUtility)

// Components that are registered globaly.
const Components = [HasError, AlertError, AlertSuccess]
Components.forEach(Component => {
   Vue.component(Component.name, Component)
})

window.Vue = Vue
window.auth = new AuthService('oauth')

new Vue({
    el: '#app',
    store,
    ...App,
    router,
});
