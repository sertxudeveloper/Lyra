<template>
  <div>
    <span class="badge badge-primary mr-1" v-for="element in field.value">{{element.value}}</span>
  </div>
</template>

<script>
  export default {
    props: ['field'],
  }
</script>

<style scoped>
  .badge {
    font-size: 85%;
  }
</style>
