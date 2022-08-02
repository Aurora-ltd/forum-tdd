<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-4">
                <textarea
                    name="body"
                    class="form-control"
                    placeholder="Have Something to Say"
                    rows="5"
                    required
                    v-model="body"
                >
                </textarea>
            </div>

            <button
                type="submit"
                class="btn btn-primary mt-4"
                @click="addReply"
            >
            Post
            </button>
        </div>

        <p class="text-center mt-4" v-else>
            Please  <a href="/login">sign in</a> to participate in this discussion
        </p>
    </div>
</template>

<script>

export default {
    props: ['endpoint'],

    data() {
        return {
            body: ''
        }
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    },

    methods: {
        addReply() {
            axios.post(this.endpoint, { body: this.body })
                .then(({data}) => {
                    this.body = '';

                    flash('Your reply has been posted.');

                    this.$emit('created', data);
            });
        }
    }
}
</script>