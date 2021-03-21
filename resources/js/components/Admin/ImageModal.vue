<template>
    <div id="image-modal">

        <img :src="path"
             :style="imageStyle"
             @click="isModal = true"
        onerror='this.style.display="none"' />

        <b-modal
            v-model="isModal"
            hide-footer
            hide-header
            size="lg"
            body-class="p-0"
            dialog-class="w-100 mw-100"
            centered
            @hidden="isModal = false"
        >

            <img :src="path"
                 class="image-modal_img"
                 @click="isModal = false"
                 onerror='this.style.display="none"' />

        </b-modal>

    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    import Paginate from '../../models/Paginate'

    export default {
        name: 'image-modal',

        data() {
            return {
                isModal: false
            }
        },

        props: {
            path: {
                type: String,
                default: ''
            },
            imageStyle: {
                default: {}
            }
        },

        computed: {
        },

        methods: {
            ...mapActions([
            ]),

        },

        components: {
        },
    }
</script>
<style lang="scss" scoped>
    #image-modal {
        img {
            width: 100%;
            height: 100%;
        }
    }

    .image-modal-body {
        background-color: transparent;
    }

    .image-modal_img {
        position: absolute;
        top: 0;
        bottom: 0;
        margin: auto;
        left: 0;
        right: 0;
        max-height: 100vh;
        max-width: 100%;
    }

</style>
