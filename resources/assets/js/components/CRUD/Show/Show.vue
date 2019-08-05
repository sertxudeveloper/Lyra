<template>
  <div v-if="$root.loader === false && resource !== null">
    <h3 class="pb-3">{{ resource.labels.singular }} details</h3>

    <div class="align-items-baseline d-flex justify-content-between" v-if="resource.labels.plural !== null">
      <div class="panel box-dark-shadow px-4 py-2 w-100">
        <div v-for="field in resource.collection.data[0]" class="row field-row py-2 align-items-center"
             v-if="!isHasField(field.component)">
          <div class="col-3 text-muted">
            <span>{{ field.name }}</span><br>
            <small>{{ field.description }}</small>
          </div>
          <div class="col">
            <component :is="field.component" :field="field"></component>
          </div>
        </div>
      </div>
    </div>

    <component v-for="field in resource.collection.data[0]" :is="field.component" :key="field.path"
               v-if="isHasField(field.component)" :resource.sync="field.value" :path="field.path"
               :foreign_column="field.foreign_column"></component>
  </div>
</template>

<script>
  import IdField from '../../Fields/Read/IdField'
  import TextField from '../../Fields/Read/TextField'
  import BelongsToField from '../../Fields/Read/BelongsToField'
  import BelongsToManyField from '../../Fields/Show/BelongsToManyField'
  import HasManyField from '../../Fields/Show/HasManyField'
  import BooleanField from '../../Fields/Read/BooleanField'

  export default {
    data() {
      return {
        resource: null,
      }
    },
    methods: {
      getResource: function () {
        this.$root.enableLoader();
        this.$http.get(this.$route.fullPath).then((response) => this.resource = response.data);
      },
      isHasField: function (component) {
        return (component === 'has-many-field')
      }
    },
    components: {IdField, TextField, BelongsToField, BelongsToManyField, HasManyField, BooleanField},
    beforeMount: function () {
      this.getResource();
    }
  }
</script>

<style scoped>

</style>
