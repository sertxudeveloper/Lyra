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
            <div class="shadow rounded-md">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6 rounded-t-md">
                <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                  <component :is="`form-${field.component}`" :field="field"/>
                </div>
              </div>

              <div class="bg-gray-50 flex space-x-2 justify-end px-4 py-3 sm:px-6 text-right rounded-b-md">

                <button
                  class="bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-gray-50 leading-4 px-3 py-2 rounded-md shadow-sm text-gray-700 text-sm"
                  type="button" @click.prevent="cancel">Cancel
                </button>

<!--                <button
                  class="bg-blue-600 border border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hover:bg-blue-700 inline-flex justify-center px-4 py-2 rounded-md shadow-sm text-sm text-white"
                  type="submit" name="create">Create
                </button>-->

                <div class="relative z-0 inline-flex shadow-sm rounded-md">
                  <button v-show="saveMode === 'create'" @click="changeMode = false"
                          name="create" type="submit"
                          class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-blue-600 text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 ring-offset-2 ring-offset-gray-50 focus:z-10">
                    <span class="-mt-0.5 lowercase first-letter:uppercase">Create {{ resource?.labels?.singular }}</span>
                  </button>

                  <button v-show="saveMode === 'create-and-edit'" @click="changeMode = false"
                          name="create-and-edit" type="submit"
                          class="relative inline-flex items-center px-4 py-2 rounded-l-md bg-blue-600 text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 ring-offset-2 ring-offset-gray-50 focus:z-10">
                    <span class="-mt-0.5 lowercase first-letter:uppercase">Create & edit</span>
                  </button>

                  <div class="-ml-px relative block border-l border-l-gray-50" v-click-away="() => this.changeMode = false">
                    <button type="button" class="relative inline-flex h-full items-center px-2 py-2 rounded-r-md bg-blue-600 text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 ring-offset-2 ring-offset-gray-50"
                            @click.prevent="changeMode = !changeMode">
                      <svg class="h-5 w-5 mt-px" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.29301C5.48053 7.10553 5.73484 7.00022 6 7.00022C6.26516 7.00022 6.51947 7.10553 6.707 7.29301L10 10.586L13.293 7.29301C13.3852 7.19749 13.4956 7.12131 13.6176 7.0689C13.7396 7.01649 13.8708 6.98891 14.0036 6.98775C14.1364 6.9866 14.2681 7.0119 14.391 7.06218C14.5139 7.11246 14.6255 7.18672 14.7194 7.28061C14.8133 7.3745 14.8875 7.48615 14.9378 7.60905C14.9881 7.73195 15.0134 7.86363 15.0123 7.99641C15.0111 8.12919 14.9835 8.26041 14.9311 8.38241C14.8787 8.50441 14.8025 8.61476 14.707 8.707L10.707 12.707C10.5195 12.8945 10.2652 12.9998 10 12.9998C9.73484 12.9998 9.48053 12.8945 9.293 12.707L5.293 8.707C5.10553 8.51948 5.00021 8.26517 5.00021 8C5.00021 7.73484 5.10553 7.48053 5.293 7.29301Z"/>
                      </svg>
                    </button>

                    <Transition
                      enter-from-class="transform opacity-0 scale-95"
                      enter-to-class="transform opacity-100 scale-100"
                      enter-active-class="transition ease-out duration-100"
                      leave-from-class="transform opacity-100 scale-100"
                      leave-to-class="transform opacity-0 scale-95"
                      leave-active-class="transition ease-in duration-75">
                      <div class="origin-top-right absolute right-0 mt-2 -mr-1 w-56 rounded-md shadow-lg bg-white border border-gray-300"
                           v-if="changeMode">
                        <div class="py-1 flex flex-col">
                          <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                          <button @click.prevent="saveMode = 'create'"
                                  class="flex hover:bg-blue-100 items-center space-x-3 px-4 py-2 text-gray-700 text-sm">
                          <span class="text-blue-700 w-3.5">
                            <svg v-show="saveMode === 'create'" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M17.8668 2.65374L6.36091 14.1596L2.13323 9.93194C1.92826 9.72697 1.59591 9.72697 1.3909 9.93194L0.153717 11.1691C-0.0512543 11.3741 -0.0512543 11.7064 0.153717 11.9115L5.98972 17.7475C6.19469 17.9524 6.52704 17.9524 6.73206 17.7475L19.8463 4.63325C20.0512 4.42828 20.0512 4.09594 19.8463 3.89092L18.6091 2.65374C18.4041 2.44877 18.0718 2.44877 17.8668 2.65374Z"/>
                            </svg>
                          </span>

                            <span class="lowercase first-letter:uppercase -mt-0.5">Create {{ resource?.labels?.singular }}</span>
                          </button>

                          <button @click.prevent="saveMode = 'create-and-edit'"
                                  class="flex hover:bg-blue-100 items-center space-x-3 px-4 py-2 text-gray-700 text-sm">
                          <span class="text-blue-700 w-3.5">
                            <svg v-show="saveMode === 'create-and-edit'" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M17.8668 2.65374L6.36091 14.1596L2.13323 9.93194C1.92826 9.72697 1.59591 9.72697 1.3909 9.93194L0.153717 11.1691C-0.0512543 11.3741 -0.0512543 11.7064 0.153717 11.9115L5.98972 17.7475C6.19469 17.9524 6.52704 17.9524 6.73206 17.7475L19.8463 4.63325C20.0512 4.42828 20.0512 4.09594 19.8463 3.89092L18.6091 2.65374C18.4041 2.44877 18.0718 2.44877 17.8668 2.65374Z"/>
                            </svg>
                          </span>

                            <span class="lowercase first-letter:uppercase">Create & edit</span>
                          </button>
                        </div>
                      </div>
                    </Transition>
                  </div>
                </div>

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
      changeMode: false,
      saveMode: 'create', // create, create-and-edit
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
