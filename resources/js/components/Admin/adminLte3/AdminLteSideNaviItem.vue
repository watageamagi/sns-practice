<template>
    <li class="nav-item">
        <router-link :to="{path: path, query: query}" class="nav-link" exact :class="{'active': isActive}">
            <template v-if="icon">
                <font-awesome-icon :style="styles" :icon="icon" class="nav-icon"/>
            </template>
            <p>
                {{ title }}
                <span v-if="badge" :class="['right', 'badge', `badge-${badgeVariant}`]">{{ badge }}</span>
            </p>
        </router-link>
    </li>
</template>

<script>
    export default {
        name: 'a-lte-side-nav-item',
        props: {
            title: {
                type: String,
                required: true,
            },
            path: {
                type: String,
                required: true,
            },
            query: {
                type: Object,
                default: null,
            },
            icon: {
                type: [String, Array],
                default: '',
            },
            badge: {
                type: String,
                default: '',
            },
            badgeVariant: {
                type: String,
                default: 'danger',
            },
            iconColor: {
                type: String,
            }
        },
        computed: {
            styles() {
                return {
                    'color': this.iconColor,
                }
            },
            isActive() {
                if(this.$route.path == this.path) {
                    return true
                }
                return false
            }
        }
    };
</script>

<style lang="scss">
    .nav-pills .nav-link {
        &.active {
            color: var(--dark);
            background-color: #e0e0e0;
        }
        &:not(.active):hover {
            color: var(--dark);
        }
    }
</style>
