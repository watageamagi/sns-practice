import axios from 'axios'
import {action, getter} from '../types'
import User from '../../models/User'

// state
export const state = {
    user: {},
    key: 'app',
}

// getters
export const getters = {
    [getter.AUTH_USER]: state => state.user,
}

// mutations
export const mutations = {
    [action.UPDATE_AUTH_USER] (state, user) {
        state.user[state.key] = new User(user)
    },

    [action.UPDATE_AUTH_KEY] (state, key) {
        state.key = key
    },

    [action.UPDATE_AUTH_MODEL] (state, model) {
        state.model = model
    },
}

// actions
export const actions = {
    async [action.FETCH_AUTH_USER] ({ commit }, url) {
        const { data } = await axios.get(url)
        commit(action.UPDATE_AUTH_USER, data)
    },

    [action.UPDATE_AUTH_USER] ({ commit }, payload) {
        commit(action.UPDATE_AUTH_USER, payload)
    },

    [action.UPDATE_AUTH_KEY] ({ commit }, key) {
        commit(action.UPDATE_AUTH_KEY, key)
    },

    [action.UPDATE_AUTH_MODEL] ({ commit }, model) {
        commit(action.UPDATE_AUTH_MODEL, model)
    }
}

export default {
    state,
    mutations,
    actions,
    getters
}
