<template>
  <div class="absolute top-0 mt-16 max-w-sm mb-4 mr-4 right-0 w-full">
    <transition-group
        v-model:enter-active-class="activeClass"
        v-model:enter-from-class="enterFromClass"
        v-model:enter-to-class="enterToClass"
        v-model:leave-active-class="leaveActiveClass"
        v-model:leave-from-class="leaveFromClass"
        v-model:leave-to-class="leaveToClass"
        v-model:move-class="moveClass"
    >
      <Notification
          v-for="notification in notifications"
          :key="notification.id"
          :notification="notification"
          @close="remove(notification.id)"
      ></Notification>
    </transition-group>
  </div>
</template>

<script>
import Notification from './Notification'

export default {
  name: "NotificationWrapper",
  components: {Notification},
  data() {
    return {
      notifications: [],
      notificationId: 0,

      enterClass: 'transform ease-out duration-300 transition',
      enterFromClass: 'translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4',
      enterToClass: 'translate-y-0 opacity-100 sm:translate-x-0',
      leaveActiveClass: 'transition ease-in duration-500',
      leaveFromClass: 'opacity-100',
      leaveToClass: 'opacity-0',
      moveClass: 'transition duration-500',
      moveDelayClass: 'delay-300'
    }
  },
  methods: {
    add(options) {
      this.notifications.push({...options, id: this.notificationId})
      this.notificationId++
    },
    remove(id) {
      let index = this.notifications.findIndex(notification => notification.id === id)
      this.notifications.splice(index, 1)
    }
  },
  computed: {
    activeClass() {
      return this.notifications.length > 1
          ? [this.enterClass, this.moveDelayClass].join(' ')
          : this.enterClass
    }
  }
}
</script>

<style scoped>

</style>
