import store from '~/store'
import { getter } from '../store/types'

export default async (to, from, next) => {
    console.log(auth.check());
    if (!auth.check()) {
        next({name: 'login'})
    } else {

        if (!auth.user().isVerify) {

            if (to.name === 'verify-email') {
                next()
                return
            }

            if (to.name !== 'verify-info') {
                next({name: 'verify-info'})
                return
            }

            next()
        }
        else {

            if (to.name === 'verify-info') {
                next({name: 'top'})
                return
            }

            next()
        }
    }
}
