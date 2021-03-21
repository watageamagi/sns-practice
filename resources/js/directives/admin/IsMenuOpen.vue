<script>

    import Vue from 'vue'

    export default {
        name: 'is-menu-open',

        bind(el, binding, vnode) {
            binding.def.update(el, binding, vnode)
        },

        update (el, binding, vnode) {
            const { pathGroup } = vnode.context.$root

            el.classList.remove('menu-open', 'active')

            if (binding.value === pathGroup) {
                el.classList.add('menu-open', 'active')
                el.click()
            }
        },
    }


</script>