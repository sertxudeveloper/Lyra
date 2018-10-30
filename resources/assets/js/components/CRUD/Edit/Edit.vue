<template>
  <div v-if="$root.loader === false && resource !== null">
    <h3 class="pb-3">{{ resource.labels.singular }} details</h3>
    <div class="align-items-baseline d-flex justify-content-between" v-if="resource.labels.plural !== null">
      <div class="panel w-100">
        <div class="px-4 py-2">
          <div v-for="field in resource.collection.data[0]" class="row field-row py-2 align-items-center">
            <div class="col-3 text-muted">
              <span>{{ field.name }}</span><br>
              <small>{{ field.description }}</small>
            </div>
            <div class="col-5">
              <component :is="field.component" :field="field"></component>
            </div>
          </div>
        </div>
        <div class="px-4 py-3 text-right">
          <div>
            <button class="btn btn-primary">Update {{ resource.labels.singular.toLowerCase() }}</button>
          </div>
        </div>
      </div>
    </div>
    {{ $data }}
  </div>
</template>

<script>
  import IdField from '../../Fields/Edit/IdField'
  import TextField from '../../Fields/Edit/TextField'
  import BelongsToField from '../../Fields/Edit/BelongsToField'

  export default {
    data() {
      return {
        resource: null,
      }
    },
    methods: {
      getResource: function () {
        this.$root.enableLoader();
        this.$http.get(this.$route.fullPath.slice(0, -5)).then((response) => this.resource = response.data);
      }
    },
    components: {IdField, TextField, BelongsToField},
    beforeMount: function () {
      this.getResource();
    }
  }
</script>

<style scoped>
  .panel {
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
  }

  .field-row {
    min-height: 60px;
    border-bottom: 1px solid #dee2e6;
  }

  .field-row:last-child {
    border-bottom: none;
  }
</style>