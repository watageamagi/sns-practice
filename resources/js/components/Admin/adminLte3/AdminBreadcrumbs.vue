<template>
    <transition-group :name="transition" class="breadcrumb" tag="ol">
        <li v-for="(item, index) in list" :key="item.path" :class="{ 'breadcrumb-item': true, active: isLast(index) }">
            <template v-if="isLast(index)">{{ showName(item) }}</template>
            <router-link v-else :to="item">{{ showName(item) }}</router-link>
        </li>
    </transition-group>
</template>

<script>
    export default {
        name: 'admin-breadcrumbs',
        props: {
            list: {
                type: Array,
                required: true,
            },
            transition: {
                type: String,
                default: 'page',
            },
        },
        methods: {
            isLast(index) {
                return index === this.list.length - 1;
            },
            showName(item) {
                if (item.meta && item.meta.title) {
                    return item.meta.title;
                }
                if (item.name) {
                    return item.name;
                }

                return '';
            },
        },
    };
</script>
