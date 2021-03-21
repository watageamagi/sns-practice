<template>
    <transition name="fade">
        <div class="scrollTopBtn-wrapper" @click="scrollTop" v-if="isScrollIcon">
            <font-awesome-icon icon="chevron-up" />
        </div>
    </transition>
</template>

<script>

    export default {
        name: 'scroll-top-btn',

        data() {
            return{
                scrollY: 0
            }
        },

        computed: {
            isScrollIcon() {
                return (this.scrollY > 100) ? true : false
            }
        },

        mounted() {
            window.addEventListener('scroll', this.calculateScrollY);
        },

        beforeDestroy() {
            window.removeEventListener('scroll', this.calculateScrollY);
        },

        methods: {
            scrollTop() {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            },

            calculateScrollY() {
                this.scrollY = window.scrollY;
            }
        }

    }
</script>


<style lang="scss" scoped>
    @import "#/_mixin.scss";

    .scrollTopBtn-wrapper {
        border: 1px solid #000000;
        border-radius: 100%;
        position: fixed;
        bottom: 50px;
        right: 50px;
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 99;
        @include mq(md) {
            bottom: 15px;
            right: 15px;
        }
    }
</style>
