<template>
    <div :id="'reply-'+id" class="card mt-4">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name"
                        v-text="data.owner.name">
                    </a>
                    &nbsp;said
                    <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>

            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>

                    <button class="btn btn-info btn-sm mt-2">Update</button>
                    <button class="btn btn-dark btn-sm mt-2" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>

            <div v-else v-text="body"></div>
        </div>

        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-secondary btn-sm me-2" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
import Favorite from './Favorite.vue'
import moment from 'moment'

    export default {
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },

        computed: {
            ago() {
                return moment(this.data.created_at).fromNow() + '....'
            },
            signedIn() {
                return window.App.signedIn
            },
            canUpdate() {
                // return this.data.user_id == window.App.user.id
                return this.authorize(user => this.data.user_id == user.id)
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                })
                .then(() => {
                    this.editing = false
                    this.data.body = this.body
                    flash('Reply Updated!')
                })
                .catch(({ response }) => flash(response.data, "danger"))
            },

            destroy() {
                axios.delete('/replies/' + this.data.id)

                this.$emit('deleted', this.data.id)
            }
        }
    }
</script>