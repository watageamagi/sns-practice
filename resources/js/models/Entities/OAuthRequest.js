import Model from '@team-decorate/alcjs'

const FILLABLE = [
    'grantType', 'clientId', 'clientSecret', 'scope', 'username', 'password'
]

export default class OAuthRequest extends Model {

    constructor (data = null) {
        super()
        this.fillable = FILLABLE

        this.grantType = 'password'
        this.clientId = 2
        this.clientSecret = window.config.password_secret
        this.scope = '*'
        this.username = ''
        this.password = ''

        this.data = data
    }
}
