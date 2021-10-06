<template>
  <div class="lg:p-4">
    <h1 class="capitalize mb-4 text-3xl text-gray-800">{{ resources?.labels?.plural }}</h1>
    <div v-if="cards.length" class="gap-4 grid grid-cols-1 md:grid-cols-2 mb-4 xl:grid-cols-4">
      <component v-for="card in cards" :is="card.component" :card="card"></component>
    </div>

    <div v-if="resources?.data?.length" class="bg-white flex flex-col rounded-lg shadow w-full">
      <div class="flex items-center justify-between h-9 m-4">
        <!-- Left toolbar -->
        <div class="flex items-center gap-x-4 h-full">
          <!-- Selection tool -->
          <div class="flex items-center px-2">
            <input type="checkbox" name="selectAll" v-model="selectAll" class="cursor-pointer h-4 w-4">
            <button class="ml-1 text-gray-600 outline-none focus:ring">
              <svg class="h-5 p-1 w-5" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"></path></svg>
            </button>
          </div>

          <!-- Search tool -->
          <div class="border border-gray-300 flex h-8 items-center rounded text-gray-500">
            <div class="flex-shrink-0 h-4 mx-2 w-4">
              <svg height="16" width="16" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.8633 18.3164L15.1211 13.5742C15.0312 13.4844 14.9141 13.4375 14.7891 13.4375H14.2734C15.5039 12.0117 16.25 10.1562 16.25 8.125C16.25 3.63672 12.6133 0 8.125 0C3.63672 0 0 3.63672 0 8.125C0 12.6133 3.63672 16.25 8.125 16.25C10.1562 16.25 12.0117 15.5039 13.4375 14.2734V14.7891C13.4375 14.9141 13.4883 15.0312 13.5742 15.1211L18.3164 19.8633C18.5 20.0469 18.7969 20.0469 18.9805 19.8633L19.8633 18.9805C20.0469 18.7969 20.0469 18.5 19.8633 18.3164ZM8.125 14.375C4.67188 14.375 1.875 11.5781 1.875 8.125C1.875 4.67188 4.67188 1.875 8.125 1.875C11.5781 1.875 14.375 4.67188 14.375 8.125C14.375 11.5781 11.5781 14.375 8.125 14.375Z"></path></svg>
            </div>
            <div class="h-full w-full pr-1">
              <input type="search" name="search" class="bg-transparent h-full outline-none placeholder-gray-500 text-gray-700 text-sm w-full" placeholder="Search...">
            </div>
          </div>
        </div>

        <!-- Right toolbar -->
        <div class="flex items-center gap-x-4 h-full">
          <!-- Filter tool -->
          <button class="bg-gray-200 h-full px-3 rounded text-gray-700" @click="getResource">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.3392 0.660806L13.6565 2.34355C12.2089 0.89571 10.2092 0 8 0C3.71703 0 0.220129 3.36574 0.01 7.59655C-0.00093547 7.81648 0.176613 8 0.396806 8H1.30148C1.50642 8 1.6761 7.84026 1.68771 7.63568C1.87616 4.31126 4.62752 1.67742 8 1.67742C9.74719 1.67742 11.3276 2.38461 12.4714 3.52858L10.7254 5.27468C10.4815 5.51855 10.6542 5.93548 10.9991 5.93548H15.6129C15.8267 5.93548 16 5.76216 16 5.54839V0.934548C16 0.589677 15.583 0.416968 15.3392 0.660806V0.660806ZM15.6032 8H14.6985C14.4936 8 14.3239 8.15974 14.3123 8.36432C14.1238 11.6887 11.3725 14.3226 8 14.3226C6.25281 14.3226 4.67235 13.6154 3.52858 12.4714L5.27465 10.7253C5.51852 10.4815 5.34581 10.0645 5.00094 10.0645H0.387097C0.173323 10.0645 0 10.2378 0 10.4516V15.0655C0 15.4103 0.416968 15.583 0.660806 15.3392L2.34355 13.6565C3.79113 15.1043 5.79084 16 8 16C12.283 16 15.7799 12.6343 15.99 8.40345C16.0009 8.18352 15.8234 8 15.6032 8Z"/></svg>
          </button>

          <!-- Filter tool -->
          <button class="bg-gray-200 cursor-pointer flex focus:ring gap-1.5 gap-x-2 h-full items-center px-3 rounded text-gray-700 outline-none">
            <svg width="19" height="19" viewBox="0 0 22 22" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M20.6236 0H1.37663C0.155245 0 -0.461013 1.48186 0.404334 2.34725L8.25 10.1946V18.2187C8.24999 18.4147 8.29188 18.6085 8.37287 18.7869C8.45385 18.9654 8.57206 19.1245 8.71956 19.2535L11.4696 21.659C12.3478 22.4275 13.75 21.8172 13.75 20.6243V10.1946L21.5959 2.34725C22.4595 1.48362 21.8475 0 20.6236 0ZM12.375 9.625V20.625L9.625 18.2187V9.625L1.375 1.375H20.625L12.375 9.625Z"/></svg>
            <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"/></svg>
          </button>

          <!-- New resource button -->
          <router-link class="bg-blue-600 flex focus:ring h-full items-center px-3 rounded outline-none"
                       :to="{ name: 'resourceCreate', params: { resourceName: $route.params.resourceName } }">
            <span class="text-base text-white">New {{ resources.labels.singular }}</span>
          </router-link>
        </div>
      </div>

      <!-- Resource Table-->
      <div>
        <table class="min-w-full border-b border-gray-200">
          <thead class="bg-gray-100">
          <tr class="uppercase text-left text-xs tracking-wider text-gray-500">
            <th scope="col" class="px-6 py-4">&nbsp;</th>
            <th scope="col" v-for="field in resources.data[0]?.fields"
                class="px-6 py-4 font-medium">{{ field.name }}</th>
            <th scope="col" class="px-6 py-4">&nbsp;</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-gray-600">
          <tr v-for="resource in resources.data" :key="resource.key">
            <td class="px-6 py-4 whitespace-nowrap">
              <input type="checkbox" name="select" v-model="selected" :value="resource.key" class="cursor-pointer h-4 w-4">
            </td>
            <td v-for="field in resource.fields" class="px-6 py-4 whitespace-nowrap">
              <component :is="`index-${field.component}`" :field="field" />
            </td>
            <td class="px-6 py-4 whitespace-nowrap">&nbsp;</td>
          </tr>
          </tbody>
        </table>
        <div class="flex justify-between p-2 items-center">
          <div></div>
          <div class="flex items-center gap-x-4">
            <pagination :meta="resources.meta" @changePage="changePage" />
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: "Index",
  data() {
    return {
      resources: {},
      cards: [],
      selected: [],
      page: null,
      perPage: null
    }
  },
  mounted() {
    this.getResource()
    this.getCards()
  },
  methods: {
    getResource() {
      this.$http.get(`/resource/${this.$route.params.resourceName}?perPage=${this.perPage ?? ''}&page=${this.page ?? ''}`)
          .then(response => {
            this.resources = response.data
            this.page = this.resources.meta.current_page
            this.perPage = this.resources.meta.per_page
          })
    },
    getCards() {
      this.$http.get(`/cards/${this.$route.params.resourceName}`)
          .then(response => this.cards = response.data)
    },
    changePage(page) {
      this.page = page
      this.getResource()
    }
  },
  computed: {
    selectAll: {
      get() {
        return this.resources.data.length ? this.selected.length === this.resources.data.length : false;
      },
      set(value) {
        let selected = [];

        if (value) {
          this.resources.data.forEach(resource => selected.push(resource.key));
        }

        this.selected = selected;
      }
    }
  }
}
</script>

<style scoped>

</style>
