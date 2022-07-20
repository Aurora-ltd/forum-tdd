<script>
import axios from 'axios'

    export default {
        props: ["attributes"],

        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = false

                flash('Updated!')
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id)

                setTimeout(function() {
                    flash('Your reply has been deleted.')
                    location.reload();
                }, 3000);

            }
        }
    }
</script>