import * as getter from '../types/getters'
import * as action from '../types/actions'
import axios from 'axios'
export const state = {
    menuModal: false,
}

export const mutations = {
    [action.UPDATE_MENU_MODAL_STATUS] (state, status) {
        state.menuModal = status
    },
}

export const actions = {
    [action.UPDATE_MENU_MODAL_STATUS] ({ commit }, status) {
        commit(action.UPDATE_MENU_MODAL_STATUS, status)
    },
}

export const getters = {
    [getter.MENU_MODAL]: state => state.menuModal,
}


export default {
    state,
    mutations,
    actions,
    getters
}
