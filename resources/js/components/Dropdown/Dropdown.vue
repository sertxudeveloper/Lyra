<template>
  <div v-click-away="close" class="relative inline-block text-left h-full">
    <slot name="trigger" :toggle="toggle" />

    <transition name="fade">
      <slot v-if="visible" />
    </transition>
  </div>
</template>

<script>
import {mixin as VueClickAway} from "vue3-click-away";

export default {
  mixins: [VueClickAway],
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
