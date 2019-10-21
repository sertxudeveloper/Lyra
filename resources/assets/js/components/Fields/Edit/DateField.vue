<template>
  <div class="text-right">
    <input type="date" v-model="field.value" :title="field.name" class="form-control">
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData'],
  }
</script>

<style scoped>

</style>
