import { Model, ArrayMappable } from '@team-decorate/alcjs'
import moment from 'moment'

const FILLABEL = [
    'id',
    'name',
    'email',
    'password',
]

const PRESENTS = [
]

export default class User extends Model {

    constructor(data = null) {
        super()
        this.fillable = FILLABEL
        this.presents = PRESENTS

        this.id = 0
        this.name = ''
        this.email = ''
        this.password = ''

        this.isVerify = false
        this.createdAt = ''

        // this.arrayMap(
        // )

        this.data = data
    }

    get _model() {
        return 'User'
    }

    update(data) {
        this.data = data
        return this
    }

    get displayCreatedAt() {
        let date = ''
        if (this.createdAt) {
            date = moment(this.createdAt)
        } else if (this.createdAt) {
            date = moment(this.createdAt)
        }

        if (!date) return

        return date.format("YYYY/M/D H:mm")
    }
}
