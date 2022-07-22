<template>
    <button type="submit" :class="classes" v-on:click="toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
        </svg>
        <span v-text="count"></span>
    </button>
</template>

<script>

    export default {
        props: ['reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorite
            }
        },

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-light']
            },

            endpoint() {
                return `/replies/${this.reply.id}/favorites`
            }
        },

        methods: {
            toggle: function () {
                this.active ? this.destroy() : this.create()
            },

            destroy() {
                axios.delete(this.endpoint)

                this.active = false
                this.count--
            },

            create() {
                axios.post(this.endpoint)

                this.active = true
                this.count++
            }

        }
    }
</script>