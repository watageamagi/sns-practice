import Cookies from 'js-cookie'
import {action, getter} from '../store/types'
import store from '~/store'
import moment from 'moment'
import User from '../models/User'
import axios from 'axios'
import OAuthResult from "../models/Entities/OAuthResult"
import OAuthRequest from "../models/Entities/OAuthRequest"

export default class AuthService {

    constructor(key, model= null) {
        this.tokenKey = key + '_token'
        this.loginUrl = 'login'
        this.userUrl = 'api/user'
        this.logoutUrl = 'logout'
        this.oauthUrl = '/oauth/token'
        this.key = key
        this.model = model

        this.setKey()
        this.setModel()
        this.setUser()
    }

    setKey() {
        store.dispatch(action.UPDATE_AUTH_KEY, this.key)
    }

    setModel() {
        const model = this.model ? this.model : User
        store.dispatch(action.UPDATE_AUTH_MODEL, model)
    }

    setUser(user = new User) {
        store.dispatch(action.UPDATE_AUTH_USER, user)
    }

    get token() {
        return Cookies.get(this.tokenKey)
    }

    check() {
        return !!this.token
    }

    isNeedLoadUser() {
        return this.check() && this.user().id === 0
    }

    async fetch(url = '/api/user') {
        try {
            await store.dispatch(action.FETCH_AUTH_USER, url)
            console.log('fetch-user.success')
        } catch (e) {
            console.log('fetch-user.failed')
            this.logout(false)
        }
    }

    user() {
        return store.getters[getter.AUTH_USER][this.key]
    }

    setLoginUrl(url) {
        this.loginUrl = url
        return this
    }

    setOutUrl(url) {
        this.logoutUrl = url
        return this
    }

    setUserUrl(url) {
        this.userUrl = url
        return this
    }

    async OAuthRegister(request, success = async () => {}, failed = async () => {}) {
        try {
            await request.post('/api/register')
            const oAuthRequest = new OAuthRequest({
                username: request.email, password: request.password
            })

            const { data } = await oAuthRequest.post(this.oauthUrl)
            const oauthResult = new OAuthResult(data)

            this.setCookie(this.tokenKey, oauthResult.accessToken)
            await success()
        } catch (e) {
            console.log('register.failed')
            await failed(e)
        }
    }

    async OAuthLogin(request, failed = async () => {}) {
        try {

            const { data } = await request.post(this.oauthUrl)
            const oauthResult = new OAuthResult(data)
            this.setCookie(this.tokenKey, oauthResult.accessToken)
            this.fetch()

            console.log('login.success')
            location.reload()
        } catch (e) {
            console.log('login.failed')
            await failed(e)
        }
    }

    async login(request, failed = async () => {}) {
        try {
            const { data } = await request.post(this.loginUrl)
            store.dispatch(action.UPDATE_AUTH_USER, data)
            this.setCookie(this.tokenKey, data.api_token)
            console.log('login.success')
            location.reload()
        } catch (e) {
            console.log('login.failed')
            await failed(e)
        }
    }

    async logout(doApi=true) {
        if (doApi) {
            try {
                await axios.post(this.logoutUrl)
                console.log('logout.api.success')
            } catch (e) {
                console.log('logout.api.failed')
            }
        }
        Cookies.remove(this.tokenKey)
        store.dispatch(action.UPDATE_AUTH_USER, null)
        location.reload()
    }

    setCookie(key, value) {
        let ttl;
        if (window.Laravel) {
            ttl = moment().add(window.Laravel.ttl, 'minutes').diff(moment(), 'days', true)
        }

        Cookies.set(key, value, { expires: ttl})
    }
}
