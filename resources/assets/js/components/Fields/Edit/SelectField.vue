<template>
  <div class="form-group">
    <select class="form-control" v-model="field.value" :id="field.column">
      <option v-for="option in field.options" :value="option.key">{{ option.value }}</option>
    </select>
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData']
  }
</script>

<style scoped>

</style>
