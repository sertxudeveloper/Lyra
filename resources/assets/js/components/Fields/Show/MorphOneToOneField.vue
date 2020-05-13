<template>
  <div>
    <div v-for="field in resource" class="row field-row py-2 align-items-center mx-0" :key="field">

      <template>
        <div class="col-3 mb-1 mb-lg-0 text-muted">
          <span>{{ field.name }} <i class="fas fa-language" v-if="field.translatable"></i></span><br>
          <small>{{ field.description }}</small>
        </div>
        <div class="col-12 col-md-12 col-lg-9 col-xl-7">
          <component :is="`${field.component}-readable`" :field="field"></component>
        </div>
      </template>

    </div>
  </div>
</template>

<script>
  export default {
    props: ["resource"],
  }
</script>

<style scoped>

</style>
