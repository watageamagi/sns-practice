
export default {
    methods: {
        async loading(fn, message = 'Loading..') {
            $loading.show(message)
            try {
                await fn()
            } catch (e) {
                throw new Error(e)
            } finally {
                $loading.hide()
            }
        },

    }
}
