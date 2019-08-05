<template>
  <div v-if="$root.loader === false && resource !== null">
    <h3 class="pb-3">New {{ resource.labels.singular.toLowerCase() }}</h3>
    <div class="align-items-baseline d-flex justify-content-between" v-if="resource.labels.plural !== null">
      <div class="panel box-dark-shadow w-100">
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
            <button class="btn btn-primary" @click="create">Create {{ resource.labels.singular.toLowerCase() }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import IdField from '../../Fields/Edit/IdField'
  import TextField from '../../Fields/Edit/TextField'
  import PasswordField from '../../Fields/Edit/PasswordField'
  import BelongsToField from '../../Fields/Edit/BelongsToField'
  import BelongsToManyField from '../../Fields/Edit/BelongsToManyField'

  export default {
    components: {IdField, TextField, PasswordField, BelongsToField, BelongsToManyField},
    data() {
      return {
        resource: null,
      }
    },
    methods: {
      getResource: function () {
        this.$root.enableLoader();
        this.$http.get(this.$route.fullPath).then(response => this.resource = response.data);
      },
      create: function () {
        this.$http.post(this.$route.fullPath, this.resource).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} created successfully`);
            return this.$router.back()
          }
        })
      }
    },
    beforeMount: function () {
      this.getResource();
    }
  }
</script>

<style scoped>

</style>
