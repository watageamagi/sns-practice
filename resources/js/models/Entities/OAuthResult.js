import Model from '@team-decorate/alcjs'

const FILLABLE = [
    'accessToken', 'expiresIn', 'refreshToken', 'tokenType'
]

export default class OAuthResult extends Model {

    constructor (data = null) {
        super()
        this.fillable = FILLABLE

        this.accessToken = ''
        this.expiresIn = ''
        this.refreshToken = ''
        this.tokenType = 'Bearer'

        this.data = data
    }

    get token() {
        return this.accessToken
    }

    set token(val) {
        this.accessToken = val
    }

}
