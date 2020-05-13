<template>
  <div>

    <div class="row field-row py-2 align-items-center mx-0">
      <div data-v-dc1d6096="" class="col-3 mb-1 mb-lg-0 text-muted">
        <span>{{field.name}}</span><br>
        <small></small>
      </div>
      <div class="col-12 col-md-12 col-lg-9 col-xl-7 align-self-center">
        <div class="form-group m-0">
          <select :name="field.column" class="form-control" v-model="field.resource">
            <option hidden value="">-- Select a resource type --</option>
            <option v-for="type in field.types" :value="type.key">{{ type.value }}</option>
          </select>
        </div>
      </div>
    </div>

    <div v-for="field in field.value" class="row field-row py-2 align-items-center mx-0" :key="field">

      <template>
        <div class="col-3 mb-1 mb-lg-0 text-muted">
          <span>{{ field.name }} <i class="fas fa-language" v-if="field.translatable"></i></span><br>
          <small>{{ field.description }}</small>
        </div>
        <div class="col-12 col-md-12 col-lg-9 col-xl-7">
          <component :is="`${field.component}-editable`" :field="field"></component>
        </div>
      </template>

    </div>
  </div>
</template>

<script>
  export default {
    props: ["field", "formData"],
    watch: {
      'field.resource': function (to, from) {
        this.field.value = this.field.types.find(type => type.key === to).fields;
      }
    }
  }
</script>

<style scoped>

</style>
