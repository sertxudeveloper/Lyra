<template>
  <div class="lg:px-8 max-w-screen-xl mx-auto py-6 sm:px-6">
    <div class="flex flex-col" v-if="resource?.data">
      <!-- Toolbar -->
      <div></div>

      <!-- Fields -->
      <form ref="form" @submit.prevent="submit">
        <div class="md:grid md:grid-cols-4 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-xl font-medium leading-6 text-gray-900">Create {{ resource?.labels?.singular }}</h3>
              <p class="mt-2 text-sm text-gray-600">This is the basic information of the resource.</p>
            </div>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-3">
            <div class="shadow rounded-md overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                  <component :is="`form-${field.component}`" :field="field"/>
                </div>
              </div>

              <div class="bg-gray-50 flex space-x-2 justify-end px-4 py-3 sm:px-6 text-right">
                <button type="submit" name="create-and-edit"
                        class="focus:underline hover:underline text-blue-500 text-sm">Create & edit
                </button>

                <button
                  class="bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-gray-50 leading-4 px-3 py-2 rounded-md shadow-sm text-gray-700 text-sm"
                  type="button" @click.prevent="cancel">Cancel
                </button>

                <button
                  class="bg-blue-600 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-blue-700 inline-flex justify-center px-4 py-2 rounded-md shadow-sm text-sm text-white"
                  type="submit" name="create">Create
                </button>
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
  name: "Create",
  data() {
    return {
      resource: {},
    }
  },
  mounted() {
    this.getResource()
  },
  methods: {
    getResource() {
      this.$http.get(`/resources/${this.$route.params.resourceName}/create`)
        .then(response => {
          this.resource = response.data
        })
    },
    cancel() {
      this.$router.push({name: 'resource-index', params: {resourceName: this.$route.params.resourceName}})
    },
    submit() {
      let formData = new FormData(this.$refs.form);
      const isCreateAndEdit = document.activeElement.name === 'create-and-edit'

      this.$http.post(`/resources/${this.$route.params.resourceName}`, formData)
        .then(response => {
          this.$notify({
            type: 'success',
            title: 'Resource created',
            text: 'The resource has been created correctly.',
            timeout: 4000
          })

          if (isCreateAndEdit) {
            this.$router.replace({
              name: 'resource-edit',
              params: {resourceName: this.$route.params.resourceName, resourceId: response.data.data.key}
            })
          } else {
            this.$router.back()
          }
        })
    }
  }
}
</script>
