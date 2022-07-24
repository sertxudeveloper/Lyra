<template>
  <div class="lg:px-8 max-w-screen-xl mx-auto py-6 sm:px-6">
    <div class="flex flex-col" v-if="resource?.data">
      <!-- Toolbar -->
      <div class="md:grid md:grid-cols-4 md:gap-6">
        <div class="flex justify-between mb-2 md:col-span-3 md:col-start-2">
          <div class="flex">
            <router-link :to="{ name: 'resource-index', params: { resourceName: $route.params.resourceName } }"
                         class="bg-white h-9 hover:text-blue-500 p-2.5 rounded shadow text-gray-700 w-9">
              <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M8.21071 15.6897L8.91781 14.9826C9.08517 14.8153 9.08517 14.5439 8.91781 14.3765L3.37745 8.83621H15.5714C15.8081 8.83621 16 8.64431 16 8.40763V7.40763C16 7.17095 15.8081 6.97906 15.5714 6.97906H3.37745L8.91781 1.4387C9.08517 1.27134 9.08517 0.999983 8.91781 0.83259L8.21071 0.125518C8.04335 -0.0418393 7.77199 -0.0418393 7.6046 0.125518L0.125518 7.6046C-0.0418393 7.77195 -0.0418393 8.04331 0.125518 8.21071L7.6046 15.6898C7.77195 15.8571 8.04331 15.8571 8.21071 15.6897Z"/>
              </svg>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Fields -->
      <form ref="form" @submit.prevent="submit">
        <div class="md:grid md:grid-cols-4 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-xl font-medium leading-6 text-gray-900">Edit {{ resource?.labels?.singular }}</h3>
              <p class="mt-2 text-sm text-gray-600">This is the basic information of the resource.</p>
            </div>
          </div>

          <div class="mt-5 md:mt-0 md:col-span-3">
            <div class="shadow rounded-md overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                  <component
                    :is="`form-${field.component}`"
                    :field="field"
                    :errors="errors[field.key]"/>
                </div>
              </div>

              <div class="bg-gray-50 flex space-x-2 justify-end px-4 py-3 sm:px-6 text-right">
                <router-link
                  class="bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-gray-50 px-3 py-2 rounded-md shadow-sm text-gray-700 text-sm"
                  :to="{ name: 'resource-index', params: { resourceName: $route.params.resourceName } }">Cancel
                </router-link>

                <button
                  class="bg-blue-600 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-blue-700 inline-flex justify-center px-4 py-2 rounded-md shadow-sm text-sm text-white"
                  type="submit">Save
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
    /**
     * Get the requested resource.
     */
    getResource() {
      this.$http.get(`/resources/${this.$route.params.resourceName}/${this.$route.params.resourceId}/edit`)
        .then(response => {
          this.resource = response.data
        })
    },

    /**
     * Submit the edit form.
     */
    submit() {
      this.errors = []

      let formData = new FormData();

      for (let field of this.resource.data.fields) {
        if (field.component === 'field-image') {
          for (const image of field.value) {
            console.log('image', image)
            formData.append(`${field.key}[]`, image)
          }
        } else {
          formData.set(field.key, field.value ?? '')
        }
      }

      formData.append('updated_at', this.resource.data.updated_at)

      const config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      }

      this.$http.post(`/resources/${this.$route.params.resourceName}/${this.$route.params.resourceId}`, formData, config)
        .then(response => {
          this.$notify({
            type: 'success',
            title: 'Resource saved',
            text: 'The resource has been saved correctly.',
            timeout: 4000
          })
          this.resource = response.data
        })
        .catch(error => {
          if (error.response.status === 409) {
            this.$notify({
              type: 'error',
              title: 'Error',
              text: 'A conflict has been detected, the resource has been edited by other session.',
              timeout: 8000
            })
            return null
          }

          const data = error.response.data
          this.errors = data.errors
        })
    }
  }
}
</script>
