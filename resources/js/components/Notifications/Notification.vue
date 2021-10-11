<template>
  <component
      :is="notification.type"
      :notification="notification"
      @close="$emit('close')"
      @mouseenter="stopTimeout"
      @mouseleave="startTimeout"
  ></component>
</template>

<script>
import Success from "./Types/Success";
import Error from "./Types/Error";

export default {
  name: "Notification",
  props: ['notification'],
  emits: ['close'],
  components: {Success, Error},
  data() {
    return {
      timeoutId: null
    }
  },
  mounted() {
    this.startTimeout()
  },
  methods: {
    stopTimeout() {
      clearTimeout(this.timeoutId)
      this.timeoutId = null
    },
    startTimeout() {
      if (this.timeoutId) return null
      this.timeoutId = setTimeout(() => this.$emit('close'), this.notification.timeout)
    }
  }
}
</script>

<style scoped>

</style>
