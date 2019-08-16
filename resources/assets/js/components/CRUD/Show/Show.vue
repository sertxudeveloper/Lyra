<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-4 px-lg-5">
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
  import HasManyField from '../../Fields/Show/HasManyField'
  import BelongsToManyInverseField from '../../Fields/Show/BelongsToManyInverseField'

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
        return (component === 'has-many-field' || component === 'belongs-to-many-inverse-field')
      }
    },
    components: {HasManyField, BelongsToManyInverseField},
    beforeMount: function () {
      this.getResource();
    }
  }
</script>

<style scoped>

</style>
