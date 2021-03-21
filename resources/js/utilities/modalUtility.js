import ValidationErrorService from '../service/ValidationErrorService'
import fileReader from "./fileReader";
import linq from 'linq'

export default {

    data() {
        return {
        }
    },

    methods: {
        /**
         * 確認モーダル
         * @param title
         * @param call　return
         * @param okText String
         * @param cancelText String
         * @return Promise
         */
        $confirmModal(title, call = value => {}, okText = 'OK', cancelText = 'Cancel') {
            this.$bvModal.msgBoxConfirm(title, {
                okTitle: okText,
                cancelTitle: cancelText
            })
                .then(value => {
                    call(value)
                })
                .catch(err => {
                    // An error occurred
                })
        },

        /**
         * OKボタンのみ
         * @param title String
         * @param call return
         * @param okText String
         */
        $messageModal(title, call = value => {}, okText = 'OK') {
            this.$bvModal.msgBoxOk(title, {
                okTitle: okText,
            })
                .then(value => {
                    call(value)
                })
                .catch(err => {
                    // An error occurred
                })
        },

        /**
         *
         */
        $alertModal(message, variant='success') {
            const h = this.$createElement
            const messageVNode = h('b-alert', {
                class: ['m-0'],
                props: {
                    variant: variant,
                    show: true,
                }}, [message]
            )

            this.$bvModal.msgBoxOk([messageVNode], {
                bodyClass: "p-0",
                size: "lg",
                hideFooter: true,
                footerClass: 'd-none'
            })
                .then(value => {
                })
        },

        /**
         * ユーザー削除モーダル
         * @param user UserModal
         * @call callback
         * @fn callback
         */
        $userDeleteModal(user, call = value => {}, err= async () => {}) {
            const h = this.$createElement
            const messageVNode = h('div', { class: [''],style: {
                    whiteSpace: 'pre-wrap',
                } }, [
                `この操作はあとから取り消すことができません。\n`,
                `ユーザーID：${user.uid}\n`,
                `姓：${user.lastName}\n`,
                `名：${user.firstName}\n`,
                `メールアドレス：${user.email}\n`,
                '削除してよろしいですか？'
            ])
            this.$bvModal.msgBoxConfirm([messageVNode],{
                title: 'このユーザーを削除します',
                okVariant: 'danger',
                okTitle: '削除',
                cancelTitle: 'キャンセル',
                hideHeaderClose: false,
            })
                .then(value => {
                    call(value)
                })
                .catch(err => {
                    err()
                })
        },

        /**
         *
         * @param id
         */
        $openModal(id) {
            this.$bvModal.show(id)
        },

        /**
         *
         * @param id
         */
        $hideModal(id) {
            this.$bvModal.hide(id)
        },

        /**
         *
         * @param e Error
         * 500, 422対応
         */
        $errorModal(e) {
            const status = this.getStatus(e)

            if(status === 500) {
                this.serverError()
            }

            if(status === 422) {
                if (e.request.responseType === "blob") {
                    return this.$blobErrModal(e)
                }
                this.validationErrors(e)
            }

            if(status === 429) {
                this.tooManyError(e)
            }

            if(status === 403) {
                this.forbidden(e)
            }
        },

        serverError() {
            const h = this.$createElement
            const messageVNode = h('b-alert', {
                class: ['m-0'],
                props: {
                    variant: 'danger',
                    show: true,
                }}, ['技術的な問題が発生しています。\n処理を中断しました。']
            )

            this.msgBoxOk(messageVNode)
        },

        validationErrors(e) {
            const valid = new ValidationErrorService(e)
            const h = this.$createElement
            const messageVNode = h('b-alert', {
                class: ['m-0'],
                props: {
                    variant: 'warning',
                    show: true
                }
            }, [valid.toString(h)])

            this.msgBoxOk(messageVNode)
        },

        tooManyError(e) {
            const h = this.$createElement
            const messageVNode = h('b-alert', {
                class: ['m-0'],
                props: {
                    variant: 'warning',
                    show: true
                }
            }, ['アクセス回数を超えました。\n時間を置き再度アクセスして下さい。'])

            this.msgBoxOk(messageVNode)
        },

        forbidden(e) {
            const h = this.$createElement
            const messageVNode = h('b-alert', {
                class: ['m-0'],
                props: {
                    variant: 'warning',
                    show: true
                }
            }, [e.response.data.message])

            this.msgBoxOk(messageVNode)
        },

        getStatus(e) {
            const { status } = e.response
            return status
        },

        msgBoxOk(messageVNode, options = {}) {
            const defaultOption = {
                bodyClass: "p-0",
                size: "lg",
                hideFooter: true,
                footerClass: 'd-none'
            }

            const option = Object.assign(defaultOption, options)

            this.$bvModal.msgBoxOk([messageVNode], option)
        },


        async $blobErrModal(e) {
            const text = await fileReader(e.response.data);
            const data = JSON.parse(text)

            const h = this.$createElement

            const element = linq.from(data.errors)
                .selectMany(x => linq.from(x.value).flatten())
                .select(x => h('li', {}, [x]))
                .toArray()


            const messageVNode = h('b-alert', {
                class: ['m-0'],
                props: {
                    variant: 'warning',
                    show: true
                }
            }, [h('ul', {class: ['m-0']}, element)])

            this.msgBoxOk(messageVNode)
        }

    }



}
