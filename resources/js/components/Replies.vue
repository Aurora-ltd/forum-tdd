<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <post-reply @created="add" :endpoint="endpoint"></post-reply>
    </div>
</template>

<script>
import Reply from './Reply.vue';
import PostReply from './PostReply.vue';

export default {
    props: ['data'],

    components: { Reply, PostReply },

    data() {
        return {
            items: this.data,
            endpoint: location.pathname + '/replies'
        }
    },

    methods: {
        add(reply) {
            this.items.push(reply)

            this.$emit('added')
        },

        remove(index) {
            this.items.splice(index, 1)

            this.$emit('removed')

            flash('Reply has removed')
        }
    }
}
</script>