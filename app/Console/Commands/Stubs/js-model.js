import Model from './Parents/Model'
import ArrayMappable from './Entities/ArrayMappable'

const FILLABEL = [
    //fillable
]

export default class DummyClass extends Model{

    constructor (data = null) {
        super()
        this.fillable = FILLABEL

        //property

        this.data = data
    }

    get _model () {
        return 'DummyClass'
    }

}
