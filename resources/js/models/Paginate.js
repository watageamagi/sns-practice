import linq from 'linq'
import {snakeToCamel, camelToSnake, buildQuery } from '../utilities/stringUtility'
import store from '../store'
import axios from 'axios'

const FILLABEL = [
]

let LIMIT = 10

export default class Paginate {

    constructor (data = null, auto = true) {
        this.originalData = data

        this.currentPage = 0
        this.firstPageUrl = ''
        this.from = 0
        this.lastPage = 0
        this.lastPageUrl = ''
        this.nextPageUrl = ''
        this.path = ''
        this.perPage = 0
        this.prevPageUrl = ''
        this.to = 0
        this.total = 0
        this.data = []

        this.model = null
        this.auto = auto

        if (data) {
            this.create()
        }
    }

    get limit () {
        if (this.lastPage < LIMIT) {
            return this.lastPage
        }
        return LIMIT
    }

    get _model () {
        return 'Paginate'
    }

    get linkNumbers () {
        const page = this.getPage()
        return linq.range(this.getFrom(), this.limit)
            .select(x => {
                const active = page === x
                return {
                    active: active,
                    num: x,
                    path: this.getPath(x)
                }
            })
            .toArray()
    }

    get hasPrev () {
        if(this.prevPageUrl) {
            return '/' + this.prevPageUrl.match(/api.*/)[0]
        }
        return this.prevPageUrl
    }

    get prev () {
        const num = this.getPage() - 1
        return {
            path: this.getPath(num),
            num: num,
            disabled: !this.hasPrev
        }
    }

    get hasNext () {
        if(this.nextPageUrl) {
            return '/' + this.nextPageUrl.match(/api.*/)[0]
        }
        return this.nextPageUrl
    }

    get next () {
        const num = this.getPage() + 1
        return {
            path: this.getPath(num),
            num: num,
            disabled: !this.hasNext
        }
    }

    get hasData() {
        return this.data.length
    }

    async nextUpdate(key = null, query = null) {
        let url = this.hasNext
        if(query) {
            const q = buildQuery(query)
            url = `${url}&${q}`
        }
        const { data } = await axios.get(url)
        const d = this.data
        this.create(key ? data[key] : data)
        d.push(...this.data)
        this.data = d
    }

    getPath (num) {
        const query = Object.assign({}, store.state.route.query)
        query.page = num
        return `${this.path}?${buildQuery(query)}`

    }

    getFrom () {
        let from = 1
        const check = Math.floor(this.limit / 2)
        const page = this.getPage()
        if (this.currentPage > check) {
            from = this.currentPage - check
        }

        if (this.lastPage < page + check) {
            from = this.lastPage - (this.limit - 1)
        }

        return from
    }

    getPage () {
        let page = store.state.route.query.page || 1
        if (!this.auto) {
            page = this.currentPage
        }
        return Number(page)
    }

    setModel (model) {
        this.model = model
        return this
    }

    setLimit (num) {
        LIMIT = num
        return this
    }

    create (source = null) {

        const data = source || this.originalData

        linq.from(data)
            .select(x => {
                x.key = camelToSnake(x.key)
                return x
            })
            .where(x => this.hasOwnProperty(snakeToCamel(x.key)))
            .select(x => {
                const key = snakeToCamel(x.key)

                if (typeof this[key] === 'number') {
                    this[key] = Number(x.value)
                    return
                }

                if (key === 'data' && this.model) {
                    this[key] = linq.from(x.value)
                        .select(x => new this.model(x)).toArray()
                    return
                }

                this[key] = x.value
                return x
            })
            .toArray()

        this.path = store.state.route.path
        return this
    }

    getPostable () {

        return linq.from(this)
            .where(x => linq.from(FILLABEL).any(xs => xs === x.key))
            .where(x => x.value)
            .select(x => {
                return {
                    key: camelToSnake(x.key),
                    value: x.value
                }
            })
            .toObject('$.key', '$.value')
    }

}
