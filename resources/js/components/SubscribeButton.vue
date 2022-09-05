<template>
    <button :class="classes" @click="subscribe">{{ text }}</button>
</template>

<script>
    export default {
        props: ['active'],

        mounted() {
            this.enable = this.active;
        },

        data() {
            return {
                enable: false
            }
        },

        computed:{
            classes() {
                return ['btn', this.enable ? 'btn-success' : 'btn-danger']
            },
            text() {
                return this.enable ? 'Unsubscribe' : 'Subscribe';
            }
        },

        methods: {
            subscribe() {
                let requestType = this.enable ? 'delete' : 'post'

                axios[requestType](location.pathname + '/subscriptions');

                this.enable = ! this.enable

            }
        }
    }
</script>