<template>
    <div class="d-flex date-form align-items-end">
        <b-input type="number"
                 class="form-control"
                 v-model="year"
                 @change="onChange"
                 :class="{ 'err-form' : isErrYear }"/>
        <div class="text-secondary ml-2 mr-2">年</div>

        <b-input type="number"
               @change="onChange"
               :class="{ 'err-form' : isErrMonth }"
               class="form-control"
               v-model="month"/>
        <div class="text-secondary ml-2 mr-2">月</div>

        <b-input type="number"
                 @change="onChange"
                 :class="{ 'err-form' : isErrDay }"
                 class="form-control"
                 v-model="day"/>
        <div class="text-secondary ml-2 mr-2">日</div>

        <div class="border-right separate-line ml-2 mr-4"></div>

        <b-input type="number"
                 @change="onChange"
                 :class="{ 'err-form' : isErrTime }"
                 class="form-control"
                 v-model="hour"/>
        <div class="text-secondary ml-2 mr-2">時</div>

        <b-input type="number"
                 @change="onChange"
                 :class="{ 'err-form' : isErrMin }"
                 class="form-control"
                 v-model="minute"/>
        <div class="text-secondary ml-2 mr-2">分</div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'
    import moment from 'moment'

    export default {
        name: 'separate-date-input',

        computed: {
            ...mapGetters({
            })
        },

        props: {
            value: {
                type: String,
                default: '',
            },

            disabled: {
                type: Boolean,
                default: false
            }
        },

        data: () => ({
            year: '',
            month: '',
            day: '',
            hour: '',
            minute: '',

            isErrYear: false,
            isErrMonth: false,
            isErrDay: false,
            isErrTime: false,
            isErrMin: false,
        }),

        created() {
            this.init()
        },

        watch: {
            value() {
                this.init()
            }
        },

        methods: {
            ...mapActions([
            ]),

            init() {
                if (!this.value) return this.clearValue()
                const m = moment(this.value, 'YYYY-MM-DD HH:mm:ss')
                this.year = Number(m.get('year'))
                this.month = Number(m.get('month') + 1)
                this.day = Number(m.get('date'))
                this.hour = Number(m.get('hour'))
                this.minute = Number(m.get('minute'))
            },

            onChange() {
                this.validYear()
                this.validMonth()
                this.validDay()
                this.validHour()
                this.validMinute()

                if (this.valid()) return

                this.$emit('input', this.getDate())
            },

            getDate() {
                const y0 = ('000' + this.year).slice(-4);
                const M0 = ('0' + this.month).slice(-2);
                const d0 = ('0' + this.day).slice(-2);
                const h0 = ('0' + this.hour).slice(-2);
                const m0 = ('0' + this.minute).slice(-2);
                return y0 + '-' + M0 + '-' + d0 + ' ' + h0 + ':' + m0
            },

            valid() {
                let err = false

                if (!this.isSet) return err

                if (this.isErrYear) {
                    err = true
                }
                if (this.isErrMonth) {
                    err = true
                }
                if (this.isErrDay) {
                    err = true
                }
                if (this.isErrHour) {
                    err = true
                }
                if (this.isErrMinute) {
                    err = true
                }

                return err
            },

            validYear() {
                const thisYear = moment().year()
                const nextYear = thisYear + 1


                if (this.year < thisYear || this.year > nextYear) {
                    return this.isErrYear = true;
                }
                this.isErrYear = false
            },

            validMonth() {
                if (this.month < 1 || this.month > 12) {
                    return this.isErrMonth = true;
                }
                this.isErrMonth = false
            },

            validDay() {
                if (this.day < 1 || this.day > 31) {
                    return this.isErrDay = true
                }
                this.isErrDay = false
            },

            validHour() {
                if (this.hour < 0 || this.hour > 24) {
                    return this.isErrTime = true
                }
                this.isErrTime = false
            },

            validMinute() {
                if (this.minute < 0 || this.minute > 59) {
                    return this.isErrMin = true
                }
                this.isErrMin = false
            },

            isSet() {
                return (this.year
                    || this.month
                    || this.day
                    || this.hour
                    || this.minute)
            },

            clearValue() {
                this.year = ''
                this.month = ''
                this.day = ''
                this.hour = ''
                this.minute = ''
            }
        },

        components: {
        },
    }
</script>

<style lang="scss" scoped>

    .form-control {
        height: 50px;
        width: 100px;
        font-size: x-large;
        background-color: #9A9A9A;
        color: white;
        /*height: calc(1.0em + 0.75rem);*/
        /*font-size: 0.9rem;*/
        /*padding: 0 10px;*/
        &.err-form {
            background-color: #f2dede;
            color: red;
        }
    }

    .separate-line {
        height: 50px;
        border-color: #9A9A9A;
        border-left-width: 3px;
        border-left-style: solid;
    }

    .date-form {
        .year {
            input {
                width: 85px;
            }
        }
        input {
            text-align: center;
        }
    }
</style>

