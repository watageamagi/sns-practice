import { Model, ArrayMappable } from '@team-decorate/alcjs'


const FILLABEL = [
    'id',
    'ip',
    'description'
]

export default class MaintenanceAccess extends Model{

    constructor (data = null) {
        super()
        this.fillable = FILLABEL
        this.presents = ['description']

        this.id = 0
        this.ip = ''
        this.description = ''

        this.data = data
    }

    get _model () {
        return 'MaintenanceAccess'
    }

}
