<template>
  <div class="lg:px-8 max-w-screen-xl mx-auto py-6 sm:px-6">
    <div class="flex flex-col" v-if="resource?.data">
      <!-- Toolbar -->
      <div class="flex justify-end">
        <div class="flex mb-2 space-x-2">
          <router-link :to="{ name: 'resource-edit', params: { resourceName: $route.params.resourceName, resourceId: $route.params.resourceId } }"
                       class="bg-white h-9 hover:text-blue-500 p-2.5 rounded shadow text-gray-700 w-9">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.3628 2.30078L13.6796 0.618308C12.8553 -0.206035 11.521 -0.20616 10.6965 0.618277L0.778433 10.4715L0.0102775 15.1276C-0.0733162 15.6344 0.365527 16.0733 0.872371 15.9897L5.52846 15.2215L15.3824 5.30349C16.2052 4.48065 16.2131 3.15102 15.3628 2.30078V2.30078ZM3.77012 9.43749L9.09071 4.1514L11.8486 6.90924L6.56249 12.2298V10.9375H5.06249V9.43749H3.77012ZM2.56662 14.3166L1.6834 13.4334L2.06278 11.1338L2.63778 10.5625H3.93749V12.0625H5.43749V13.3622L4.86618 13.9372L2.56662 14.3166V14.3166ZM14.4099 4.33121L14.4083 4.3328L14.4067 4.3344L12.9058 5.84515L10.1548 3.09421L11.6656 1.59328L11.6671 1.59171L11.6687 1.59015C11.9546 1.30434 12.418 1.30081 12.7073 1.59012L14.3903 3.27306C14.699 3.58171 14.7009 4.04021 14.4099 4.33121V4.33121Z"/></svg>
          </router-link>
          <button @click="remove(resource.key)" class="bg-white h-9 hover:text-red-500 p-2.5 rounded shadow text-gray-700 w-9">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9.375 13H10.125C10.2245 13 10.3198 12.9605 10.3902 12.8902C10.4605 12.8198 10.5 12.7245 10.5 12.625V5.875C10.5 5.77554 10.4605 5.68016 10.3902 5.60984C10.3198 5.53951 10.2245 5.5 10.125 5.5H9.375C9.27554 5.5 9.18016 5.53951 9.10983 5.60984C9.03951 5.68016 9 5.77554 9 5.875V12.625C9 12.7245 9.03951 12.8198 9.10983 12.8902C9.18016 12.9605 9.27554 13 9.375 13ZM14.5 2.5H11.9247L10.8622 0.728125C10.7288 0.505942 10.5402 0.322091 10.3147 0.194487C10.0892 0.066882 9.83444 -0.000123231 9.57531 1.70139e-07H6.42469C6.16567 -1.5274e-05 5.91106 0.0670412 5.68566 0.194641C5.46025 0.32224 5.27172 0.506033 5.13844 0.728125L4.07531 2.5H1.5C1.36739 2.5 1.24021 2.55268 1.14645 2.64645C1.05268 2.74022 1 2.86739 1 3V3.5C1 3.63261 1.05268 3.75979 1.14645 3.85355C1.24021 3.94732 1.36739 4 1.5 4H2V14.5C2 14.8978 2.15804 15.2794 2.43934 15.5607C2.72064 15.842 3.10218 16 3.5 16H12.5C12.8978 16 13.2794 15.842 13.5607 15.5607C13.842 15.2794 14 14.8978 14 14.5V4H14.5C14.6326 4 14.7598 3.94732 14.8536 3.85355C14.9473 3.75979 15 3.63261 15 3.5V3C15 2.86739 14.9473 2.74022 14.8536 2.64645C14.7598 2.55268 14.6326 2.5 14.5 2.5ZM6.37 1.59094C6.38671 1.56312 6.41035 1.54012 6.43862 1.52418C6.46688 1.50824 6.4988 1.49991 6.53125 1.5H9.46875C9.50115 1.49996 9.533 1.50832 9.5612 1.52426C9.58941 1.54019 9.613 1.56317 9.62969 1.59094L10.1753 2.5H5.82469L6.37 1.59094ZM12.5 14.5H3.5V4H12.5V14.5ZM5.875 13H6.625C6.72446 13 6.81984 12.9605 6.89016 12.8902C6.96049 12.8198 7 12.7245 7 12.625V5.875C7 5.77554 6.96049 5.68016 6.89016 5.60984C6.81984 5.53951 6.72446 5.5 6.625 5.5H5.875C5.77554 5.5 5.68016 5.53951 5.60984 5.60984C5.53951 5.68016 5.5 5.77554 5.5 5.875V12.625C5.5 12.7245 5.53951 12.8198 5.60984 12.8902C5.68016 12.9605 5.77554 13 5.875 13Z"/></svg>
          </button>
        </div>
      </div>

      <!-- Fields -->
      <div class="md:grid md:grid-cols-4 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-xl font-medium leading-6 text-gray-900">{{ resource?.labels?.singular }} details</h3>
            <p class="mt-2 text-sm text-gray-600">This information will be displayed publicly so be careful what you share.</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-3">
          <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
              <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                <component :is="`detail-${field.component}`" :field="field" />
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
