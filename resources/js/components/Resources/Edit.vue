<template>
  <div class="lg:px-8 max-w-screen-xl mx-auto py-6 sm:px-6">
    <!--    <h1 class="capitalize mb-4 text-3xl text-gray-800">{{ resource?.labels?.singular }} details</h1>-->
    <div class="flex flex-col" v-if="resource?.data">
      <!-- Toolbar -->
      <div></div>

      <!-- Fields -->
      <form ref="form" @submit.prevent="submit">
        <input type="hidden" name="updated_at" v-model="resource.data.updated_at">

        <div class="md:grid md:grid-cols-4 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-xl font-medium leading-6 text-gray-900">Edit {{ resource?.labels?.singular }}</h3>
              <p class="mt-2 text-sm text-gray-600">This information will be displayed publicly so be careful what you share.</p>
            </div>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-3">
            <div class="shadow rounded-md overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                  <component :is="`form-${field.component}`" :field="field" :errors="errors[field.key]" />
                </div>
              </div>
              <div class="bg-gray-50 flex space-x-2 justify-end px-4 py-3 sm:px-6 text-right">
                <button @click="cancel"
                    class="bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-gray-50 leading-4 px-3 py-2 rounded-md shadow-sm text-gray-700 text-sm">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-blue-700 inline-flex justify-center px-4 py-2 rounded-md shadow-sm text-sm text-white">Save</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "Edit",
  data() {
    return {
      resource: {},
      errors: {},
    }
  },
  mounted() {
    this.getResource()
  },
  methods: {
    getResource() {
      this.$http.get(`/resources/${this.$route.params.resourceName}/${this.$route.params.resourceId}/edit`)
          .then(response => {
            this.resource = response.data
          })
    },
    cancel() {
      this.$router.push({ name: 'resource-index', params: { resourceName: this.$route.params.resourceName } })
    },
    submit() {
      this.errors = []

      let formData = new FormData(this.$refs.form);

      this.$http.post(`/resources/${this.$route.params.resourceName}/${this.$route.params.resourceId}`, formData)
          .then(response => {
            this.$notify({ type: 'success', title: 'Resource saved', text: 'The resource has been saved correctly.', timeout: 2000 })
            this.resource = response.data
          })
          .catch(error => {
            if (error.response.status === 409) {
              this.$notify({ type: 'error', title: 'Error', text: 'A conflict has been detected, the resource has been edited by other session.', timeout: 5000 })
              return null
            }

            const data = error.response.data
            this.errors = data.errors
          })
    }
  }
}
</script>

<style scoped>

</style>
