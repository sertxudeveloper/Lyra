<template>
  <div v-click-away="close" class="relative inline-block text-left h-full">
    <slot name="trigger" :toggle="toggle" />

    <transition
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      enter-active-class="transition ease-out duration-100"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
      leave-active-class="transition ease-in duration-75"
    >
      <slot v-if="visible" />
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      visible: false
    };
  },
  methods: {
    toggle() {
      this.visible = !this.visible;
    },
    close() {
      this.visible = false;
    }
  }
}
</script>
