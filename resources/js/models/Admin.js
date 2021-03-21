import { Model, ArrayMappable } from '@team-decorate/alcjs'

const FILLABEL = [
    'id', 'name', 'email', 'password', 'type'
]

export default class Admin extends Model{

    constructor (data = null) {
        super()
        this.fillable = FILLABEL

        this.id = 0
        this.name = ''
        this.email = ''
        this.password = ''
        this.type = 0

        this.data = data
    }

    get _model () {
        return 'Admin'
    }

    isDeveloper() {
        return this.type
    }

}
