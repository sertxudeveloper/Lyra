<template>
  <a :href="field.value">{{ field.value }}</a>
</template>

<script>
  export default {
    props: ['field']
  }
</script>

<style scoped>

</style>
