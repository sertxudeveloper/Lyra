<template>
  <div class="align-items-center d-flex h-100 justify-content-center px-2 dropdown">
    <a href="#" role="button" id="notifyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
      @click="getNotifications">
      <i class="fas fa-bell"></i>
      <span class="badge badge-danger" v-if="notifications.length">{{ notifications.length }}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right dropdown-large" aria-labelledby="notifyDropdown">
      <div class="list-group">

        <template v-if="notifications.length">
          <a v-for="notification in notifications" @click.prevent="markAsRead(notification)"
            class="border-left-0 border-right-0 list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1" :class="{ 'font-weight-bold': !notification.read }">{{ notification.title }}</h6>
              <small>{{ notification.date }}</small>
            </div>
            <span class="mb-1" style="font-size: 0.85rem;">{{ notification.message }}</span>
          </a>
          <small class="text-center text-muted mt-2">Click the notification to mark it as read</small>
        </template>

        <template v-else>
          <span class="py-5 text-center text-muted">You haven't got any notification</span>
        </template>

      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        notifications: []
      }
    },
    methods: {
      getNotifications() {
        this.$http.get('/notifications').then((response) => this.notifications = response.data.data);
      },
      markAsRead(notification) {
        if (notification.read) return null;
        this.$http.get('/notifications/' + notification.id).then((response) => this.notifications = response.data.data);
        setTimeout(() => this.getNotifications(), 5000);
      }
    },
    mounted() {
      this.getNotifications();
    }
  }
</script>

<style scoped>

</style>
