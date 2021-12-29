<template>
  <div class="max-w-screen-lg mx-auto py-6">
    <div class="flex flex-col">
      <!-- Toolbar -->
      <div class="flex justify-between mb-4 md:col-span-3 md:col-start-2">
        <!-- Back button -->
        <div class="flex">
          <router-link :to="{ name: 'resource-index', params: { resourceName: $route.params.resourceName } }"
                       class="bg-white h-9 hover:text-blue-500 p-2.5 rounded shadow text-gray-700 w-9">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M8.21071 15.6897L8.91781 14.9826C9.08517 14.8153 9.08517 14.5439 8.91781 14.3765L3.37745 8.83621H15.5714C15.8081 8.83621 16 8.64431 16 8.40763V7.40763C16 7.17095 15.8081 6.97906 15.5714 6.97906H3.37745L8.91781 1.4387C9.08517 1.27134 9.08517 0.999983 8.91781 0.83259L8.21071 0.125518C8.04335 -0.0418393 7.77199 -0.0418393 7.6046 0.125518L0.125518 7.6046C-0.0418393 7.77195 -0.0418393 8.04331 0.125518 8.21071L7.6046 15.6898C7.77195 15.8571 8.04331 15.8571 8.21071 15.6897Z"/></svg>
          </router-link>
        </div>

        <div class="flex space-x-2">
          <!-- Edit button -->
          <router-link :to="{ name: 'resource-edit', params: { resourceName: $route.params.resourceName, resourceId: $route.params.resourceId } }"
                       class="bg-white h-9 hover:text-blue-500 p-2 rounded shadow text-gray-700 w-9">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13.9823 3.36938L12.6147 2.00238C11.9449 1.3326 10.8608 1.3325 10.1909 2.00235L2.13248 10.0081L1.50835 13.7912C1.44043 14.2029 1.79699 14.5596 2.2088 14.4917L5.99188 13.8675L13.9982 5.80909C14.6667 5.14053 14.6731 4.06021 13.9823 3.36938V3.36938ZM4.56322 9.16796L8.8862 4.87301L11.1269 7.11376L6.83203 11.4367V10.3867H5.61328V9.16796H4.56322ZM3.58538 13.1322L2.86776 12.4146L3.17601 10.5462L3.64319 10.082H4.69921V11.3008H5.91796V12.3568L5.45377 12.824L3.58538 13.1322V13.1322ZM13.208 5.01911L13.2067 5.0204L13.2055 5.0217L11.9859 6.24918L9.7508 4.01405L10.9783 2.79454L10.9796 2.79327L10.9808 2.792C11.2131 2.55978 11.5896 2.55691 11.8247 2.79197L13.1921 4.15936C13.4429 4.41014 13.4445 4.78267 13.208 5.01911V5.01911Z"/></svg>
          </router-link>

          <!-- Remove button -->
          <button
              v-if="!resource.data.trashed"
              @click="deleteResource(resource.data.key)"
              class="bg-white h-9 hover:text-red-500 p-2 rounded shadow text-gray-700 w-9">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9.10474 12.0625H9.71411C9.79492 12.0625 9.87242 12.0304 9.92956 11.9733C9.9867 11.9161 10.0188 11.8386 10.0188 11.7578V6.27344C10.0188 6.19263 9.9867 6.11513 9.92956 6.05799C9.87242 6.00085 9.79492 5.96875 9.71411 5.96875H9.10474C9.02393 5.96875 8.94643 6.00085 8.88929 6.05799C8.83215 6.11513 8.80005 6.19263 8.80005 6.27344V11.7578C8.80005 11.8386 8.83215 11.9161 8.88929 11.9733C8.94643 12.0304 9.02393 12.0625 9.10474 12.0625ZM13.2688 3.53125H11.1764L10.3131 2.0916C10.2047 1.91108 10.0515 1.7617 9.86824 1.65802C9.685 1.55434 9.47803 1.4999 9.26749 1.5H6.70761C6.49716 1.49999 6.29029 1.55447 6.10714 1.65815C5.924 1.76182 5.77082 1.91115 5.66253 2.0916L4.79874 3.53125H2.7063C2.59855 3.53125 2.49522 3.57405 2.41904 3.65024C2.34285 3.72642 2.30005 3.82976 2.30005 3.9375V4.34375C2.30005 4.45149 2.34285 4.55483 2.41904 4.63101C2.49522 4.7072 2.59855 4.75 2.7063 4.75H3.11255V13.2812C3.11255 13.6045 3.24095 13.9145 3.46951 14.143C3.69807 14.3716 4.00807 14.5 4.3313 14.5H11.6438C11.967 14.5 12.277 14.3716 12.5056 14.143C12.7341 13.9145 12.8625 13.6045 12.8625 13.2812V4.75H13.2688C13.3765 4.75 13.4799 4.7072 13.5561 4.63101C13.6322 4.55483 13.675 4.45149 13.675 4.34375V3.9375C13.675 3.82976 13.6322 3.72642 13.5561 3.65024C13.4799 3.57405 13.3765 3.53125 13.2688 3.53125ZM6.66317 2.79264C6.67675 2.77004 6.69596 2.75135 6.71893 2.7384C6.74189 2.72545 6.76783 2.71868 6.79419 2.71875H9.18091C9.20723 2.71872 9.23311 2.72551 9.25603 2.73846C9.27894 2.75141 9.29811 2.77007 9.31167 2.79264L9.75499 3.53125H6.22011L6.66317 2.79264ZM11.6438 13.2812H4.3313V4.75H11.6438V13.2812ZM6.26099 12.0625H6.87036C6.95117 12.0625 7.02867 12.0304 7.08581 11.9733C7.14295 11.9161 7.17505 11.8386 7.17505 11.7578V6.27344C7.17505 6.19263 7.14295 6.11513 7.08581 6.05799C7.02867 6.00085 6.95117 5.96875 6.87036 5.96875H6.26099C6.18018 5.96875 6.10268 6.00085 6.04554 6.05799C5.9884 6.11513 5.9563 6.19263 5.9563 6.27344V11.7578C5.9563 11.8386 5.9884 11.9161 6.04554 11.9733C6.10268 12.0304 6.18018 12.0625 6.26099 12.0625Z"/></svg>
          </button>

          <!-- Restore button -->
          <button
              v-else
              @click="restoreResource(resource.data.key)"
              class="bg-white h-9 hover:text-blue-500 p-2 rounded shadow text-gray-700 w-9">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M7.98807 1.50001C6.25118 1.50313 4.67417 2.18757 3.50989 3.30022L2.57384 2.36417C2.17755 1.96788 1.5 2.24853 1.5 2.80895V6.32259C1.5 6.67 1.78162 6.95162 2.12903 6.95162H5.64267C6.20309 6.95162 6.48374 6.27407 6.08748 5.87778L4.99322 4.78353C5.80216 4.0261 6.84889 3.60653 7.96094 3.59694C10.3827 3.57603 12.424 5.53585 12.4031 8.03796C12.3832 10.4115 10.4589 12.4032 8 12.4032C6.92207 12.4032 5.9033 12.0185 5.10042 11.3141C4.97611 11.205 4.78837 11.2116 4.67142 11.3286L3.63189 12.3681C3.5042 12.4958 3.51052 12.704 3.64453 12.825C4.79712 13.8661 6.32452 14.5 8 14.5C11.5898 14.5 14.5 11.5899 14.5 8.00006C14.5 4.41434 11.5738 1.49359 7.98807 1.50001Z"/></svg>
          </button>
        </div>
      </div>

      <!-- Resource title --->
      <div v-if="resource.labels.singular" class="mb-4">
        <h3 class="text-xl font-medium leading-6 text-gray-900">{{ resource.labels.singular }} details</h3>
        <p class="mt-2 text-sm text-gray-600">{{ resource.description }}</p>
      </div>

      <!-- Fields -->
      <div class="shadow rounded-md bg-white">
        <Loading :loading="loading">
          <div class="mt-5 md:mt-0">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div
                    v-for="field in resource.data.fields"
                    class="gap-6 grid grid-cols-3">
                  <component
                      :is="`detail-${field.component}`"
                      :field="field" />
                </div>
              </div>
            </div>
          </div>
        </Loading>
      </div>
<!--      <div class="max-w-screen-lg">
        <div class="md:col-span-1">
          <div>
            <h3 class="text-xl font-medium leading-6 text-gray-900">{{ resource.labels?.singular }} details</h3>
            <p class="mt-2 text-sm text-gray-600">This information will be displayed publicly so be careful what you share.</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0">
          <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
              <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                <component :is="`detail-${field.component}`" :field="field" />
              </div>
            </div>
          </div>
        </div>
      </div>-->
    </div>
  </div>
</template>

<script>
export default {
  name: "Show",
  data() {
    return {
      loading: true,

      resource: {
        key: null,
        labels: [],
        data: [],
      },
    }
  },
  mounted() {
    this.getResource()
  },
  methods: {
    getResource() {
      this.loading = true

      this.$http.get(`/resources/${this.$route.params.resourceName}/${this.$route.params.resourceId}`)
          .then(({ data }) => {
            this.resource = data
            this.loading = false
          })
    },

    /**
     * Delete the specified resource.
     */
    deleteResource(key) {
      this.$http.delete(`/resources/${this.$route.params.resourceName}/${key}`)
          .then(() => {
            this.$notify({ type: 'success', title: 'Resource removed', text: 'The resource has been deleted correctly.', timeout: 4000 })

            this.getResource()
          })
    },

    /**
     * Restore the specified resource.
     */
    restoreResource(key) {
      this.$http.post(`/resources/${this.$route.params.resourceName}/${key}/restore`)
          .then(() => {
            this.$notify({ type: 'success', title: 'Resource restored', text: 'The resource has been restored correctly.', timeout: 4000 })

            this.getResource()
          })
    },
  }
}
</script>
