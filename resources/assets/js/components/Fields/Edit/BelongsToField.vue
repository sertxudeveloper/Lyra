<template>
  <div class="form-group">
    <select class="form-control" v-model="field.value" :disabled="field.disabled">
      <option disabled value="">Select a {{field.name.toLowerCase()}}</option>
      <option v-for="element in field.options" :value="element.key">{{element.value}}</option>
    </select>
  </div>
</template>

<script>
  export default {
    props: ['field']
  }
</script>

<style scoped>

</style>
