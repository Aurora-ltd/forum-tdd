<template>
  <div class="dropdown" v-if="notifications.length">
    <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
      </svg>
    </a>

    <ul class="dropdown-menu">
      <li v-for="notification in notifications" v-bind:key="notification">
        <a
          :href="notification.data.link"
          class="dropdown-item"
          v-text="notification.data.message"
          @click.prevent="markAsRead(notification)"
        >
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return { notifications: false }
  },

  created() {
    axios.get("/profiles/"+window.App.user.name+"/notifications")
      .then(response => this.notifications = response.data)
  },

  methods: {
    markAsRead($notification) {
      axios.delete('/profiles/' + window.App.user.name + '/notifications/' + $notification.id)
    }
  }
}
</script>