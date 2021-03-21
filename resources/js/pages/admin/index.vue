<template>
    <div id="app">
        <loading ref="loading"/>
        <spinner-loading :loading="pageLoading" align="page-loading"></spinner-loading>
        <transition name="page" mode="out-in">
            <component v-if="layout" :is="layout"/>
        </transition>
    </div>
</template>

<script>

    import Loading from '../../components/Ui/Loading'
    import SpinnerLoading from "../../components/Ui/SpinnerLoading";

    // Load layout components dynamically.
    const requireContext = require.context('../../layouts/admin', false, /.*\.vue$/)

    const layouts = requireContext.keys()
        .map(file =>
            [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
        )
        .reduce((components, [name, component]) => {
            components[name] = component.default || component
            return components
        }, {})

    export default {
        el: '#admin',

        data: () => ({
            pathGroup: null,
            layout: null,
            defaultLayout: 'app',
            pageLoading: false,
            loadCount: 0,
        }),

        metaInfo () {
            const { appName } = window.config

            return {
                title: appName,
                titleTemplate: `%s · ${appName}`
            }
        },

        watch: {
            $route ($new) {
                this.pathGroup = $new.meta.group
            }
        },

        computed: {
        },

        mounted () {
            this.$loading = this.$refs.loading
            document.body.className = 'sidebar-mini'
            window.addEventListener('resize', this.calculateWindowWidth);
        },

        methods: {
            /**
             * Set the application layout.
             *
             * @param {String} layout
             */
            setLayout (layout) {
                if (!layout || !layouts[layout]) {
                    layout = this.defaultLayout
                }

                this.layout = layouts[layout]
            },

            async setPageLoading(call = async () => {}, err = async () => {} ) {
                this.pageLoading = true
                this.loadCount ++
                try {
                    await call()
                } catch (e) {
                    await err(e)
                } finally {
                    this.loadCount --
                    if (!this.loadCount) {
                        this.pageLoading = false
                    }
                }
            },

            calculateWindowWidth() {
                if(window.innerWidth <= 992) {
                    document.body.className = 'sidebar-mini sidebar-closed sidebar-collapse'
                }
                if(window.innerWidth > 992) {
                    document.body.className = 'sidebar-mini'
                }
            }

        },

        beforeDestroy() {
            window.removeEventListener('resize', this.calculateWindowWidth);
        },

        components: {
            SpinnerLoading,
            Loading
        },
    }
</script>
