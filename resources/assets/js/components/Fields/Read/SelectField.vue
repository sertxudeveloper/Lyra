<template>
  <span>{{field.value}}</span>
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
