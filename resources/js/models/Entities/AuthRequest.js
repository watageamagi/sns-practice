import Model from '@team-decorate/alcjs'

const FILLABLE = [
    'name', 'email', 'password', 'remember'
]

export default class AuthRequest extends Model {

    constructor (data = null) {
        super()
        this.fillable = FILLABLE

        this.name = ''
        this.email = ''
        this.password = ''
        this.remember = ''

        this.data = data
    }
}
