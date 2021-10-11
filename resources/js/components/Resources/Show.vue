<template>
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<!--    <h1 class="capitalize mb-4 text-3xl text-gray-800">{{ resource?.labels?.singular }} details</h1>-->
    <div class="flex flex-col" v-if="resource?.data">
      <!-- Toolbar -->
      <div></div>

      <!-- Fields -->
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-xl font-medium leading-6 text-gray-900">{{ resource?.labels?.singular }} details</h3>
            <p class="mt-2 text-sm text-gray-600">This information will be displayed publicly so be careful what you share.</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
              <div v-for="field in resource.data.fields">
                <component :is="`details-${field.component}`" :field="field" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Show",
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
      this.$http.get(`/resources/${this.$route.params.resourceName}/${this.$route.params.resourceId}`)
          .then(response => {
            this.resource = response.data
          })
    },
  }
}
</script>

<style scoped>

</style>
