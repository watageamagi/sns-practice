import store from '~/store'
import { getter } from '../store/types'

export default (to, from, next) => {
  if (auth.check()) {
      next({ name: 'top' })
  } else {
      next()
  }
}
