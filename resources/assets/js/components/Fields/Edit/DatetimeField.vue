<template>
  <div class="text-right">
    <input type="datetime-local" v-model="field.value" :title="field.name" class="form-control" step="1">
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData'],
  }
</script>

<style scoped>

</style>
