<template>
  <span>
    <i class="fas fa-times-circle" v-if="!field.value"></i>
    <i class="fas fa-check-circle" v-else></i>
  </span>
</template>

<script>
  export default {
    props: ['field']
  }
</script>

<style scoped>
  .fa-times-circle {
    color: #bd2130;
  }

  .fa-check-circle {
    color: #218838;
  }
</style>
