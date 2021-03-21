<script>

    import Vue from 'vue'
    import linq from 'linq'

    export default {
        name: 'btn-loading',

        bind(el, binding, vnode) {
            if (!vnode.context.__cacheInnerHtml) {
                vnode.context.__cacheInnerHtml = {}
            }
            vnode.context.__cacheInnerHtml[binding.expression] = el.innerHTML
        },

        update (el, binding, vnode) {
            const innerHtml = vnode.context.__cacheInnerHtml[binding.expression]
            if (binding.value) {
                const html = `<i class="fa fa-refresh fa-spin"></i>${el.innerHTML}`
                el.disabled = true
                el.innerHTML = html
            } else {
                if (binding.oldValue) {
                    el.disabled = false
                    el.innerHTML = innerHtml.replace('<i class="fa fa-refresh fa-spin"></i>', '')
                }

                if (vnode.data.attrs && vnode.data.attrs.disabled) {
                    el.disabled = true
                }
            }
        },
    }


</script>