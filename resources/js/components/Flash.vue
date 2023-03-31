<template>
    <div
        class="alert "
        :class="`alert-${level}`"
        role="alert"
        v-show="show">
        {{ data.message }}
    </div>
</template>

<script>
    export default {
        props: ['initialData'],

        data() {
            return {
                data: {},
                show: false
            }
        },

        created() {
            if(this.initialData) {
                this.data = this.initialData
            }
            window.events.$on(
                'flash', data => (this.data = data)
                )
        },

        methods: {
            flash() {
                this.show = true

                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        },

        computed: {
            level() {
                return this.data.level || "success"
            }
        },

        watch: {
            data() {
                if(this.data.message !== null) {
                    this.flash()
                }
            }
        }
    }
</script>

<style>
.alert {
    position: fixed;
    right: 25px;
    bottom: 25px;
}
</style>
