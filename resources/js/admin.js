import Vue from 'vue'
import './plugins/admin'
import store from './store'
import App from './pages/admin'
import router from './router/admin'
import BootStrapVue from 'bootstrap-vue'
import AuthService from './service/AuthService'
import VueAdminLte from '@cookieseater/vue-adminlte3'
import modalUtility from './utilities/modalUtility'

Vue.use(BootStrapVue)
Vue.use(VueAdminLte)
Vue.mixin(modalUtility)

window.Vue = Vue

window.auth = new AuthService('admin')

new Vue({
    el: '#admin',
    store,
    ...App,
    router,
});

