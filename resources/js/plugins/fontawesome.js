import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/free-regular-svg-icons'

import {
    fas
} from '@fortawesome/free-solid-svg-icons'

import {
    fab
} from '@fortawesome/free-brands-svg-icons'

import  {
    far
} from '@fortawesome/free-regular-svg-icons'

library.add(
    fab, fas, far
)

Vue.component('font-awesome-icon', FontAwesomeIcon)
