<template>
  <div class="form-group">
    <div class="custom-control custom-radio" v-for="option in field.options">
      <input type="radio" :id="`${field.column}_${option.key}`" class="custom-control-input"
             :name="field.column" :value="option.key" v-model="field.value">
      <label class="custom-control-label" :for="`${field.column}_${option.key}`">{{ option.value }}</label>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData']
  }
</script>

<style scoped>

</style>
