<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-4 px-lg-5">
    <h3 class="pb-3">{{ resource.labels.singular }} details</h3>
    <div class="align-items-baseline d-flex justify-content-between" v-if="resource.labels.plural !== null">
        <div class="panel box-dark-shadow w-100">
          <div class="px-4 py-2">
            <div v-for="field in resource.collection.data[0]" class="row field-row py-2">
              <div class="col-3 my-lg-2 mb-1 mb-lg-0 text-muted">
                <span>{{ field.name }}</span><br>
                <small>{{ field.description }}</small>
              </div>
              <div class="col-12 col-md-12 col-lg-9 col-xl-6">
                <component :is="`${field.component}-editable`" :field="field" :formData="formData"></component>
              </div>
            </div>
          </div>
        </div>
        <div class="px-4 py-3 text-right">
          <div>
            <button class="btn btn-primary" @click="edit">Update {{ resource.labels.singular.toLowerCase() }}</button>
          </div>
        </div>
      </div>
    </div>
    {{ $data }}
  </div>
</template>

<script>
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
      edit: function () {
        this.$http.put(this.$route.fullPath, this.resource).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} edited successfully`);
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
