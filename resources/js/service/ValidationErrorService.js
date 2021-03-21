import linq from 'linq'

export default class ValidationErrorService {

    constructor(e) {
        this.response = e.response
        this.errors = this.response.data.errors
    }

    toString(h) {
        const e = linq.from(this.errors)
            .selectMany(x => linq.from(x.value).flatten())
            .select(x => h('li', {}, [x]))
            .toArray()

        return h('ul', {}, e)
    }


}