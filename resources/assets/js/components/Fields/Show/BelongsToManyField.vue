<template>
  <div>
    <template v-for="element in field.value">
      <span>{{element.key}} - {{element.value}}</span>
      <br>
    </template>
  </div>
</template>

<script>
  export default {
    props: ['field']
  }
</script>

<style scoped>

</style>
