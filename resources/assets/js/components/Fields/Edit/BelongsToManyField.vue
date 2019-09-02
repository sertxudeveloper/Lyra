<template>
  <div class="form-group">
    <multiselect
      v-model="field.value" :options="field.options" :multiple="true" :close-on-select="false"
      :clear-on-select="false" :preserve-search="true" placeholder="Pick some"
      label="value" track-by="value" :preselect-first="false"></multiselect>
  </div>
</template>

<script>
  import Multiselect from 'vue-multiselect'

  export default {
    components: {Multiselect},
    props: ['field'],
  }
</script>

<style scoped>

</style>
