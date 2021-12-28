<template>
  <!-- Resource Index -->
  <div class="xl:px-4 xl:pb-4">
    <h1 class="capitalize mb-4 text-2xl text-gray-800">{{ resources.labels?.plural ?? "&nbsp;" }}</h1>

    <!-- Cards -->
    <div v-if="cards.length" class="gap-4 grid grid-cols-1 md:grid-cols-2 mb-4 xl:grid-cols-4">
      <component v-for="card in cards" :is="card.component" :card="card"></component>
    </div>

    <!-- Toolbar row -->
    <div class="flex items-center justify-between h-9 my-4">
      <!-- Search tool -->
      <div class="bg-white border border-gray-300 flex h-10 items-center rounded-md text-gray-500">
        <div class="flex-shrink-0 h-4 ml-3 mr-2 w-4">
          <svg height="16" width="16" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.8633 18.3164L15.1211 13.5742C15.0312 13.4844 14.9141 13.4375 14.7891 13.4375H14.2734C15.5039 12.0117 16.25 10.1562 16.25 8.125C16.25 3.63672 12.6133 0 8.125 0C3.63672 0 0 3.63672 0 8.125C0 12.6133 3.63672 16.25 8.125 16.25C10.1562 16.25 12.0117 15.5039 13.4375 14.2734V14.7891C13.4375 14.9141 13.4883 15.0312 13.5742 15.1211L18.3164 19.8633C18.5 20.0469 18.7969 20.0469 18.9805 19.8633L19.8633 18.9805C20.0469 18.7969 20.0469 18.5 19.8633 18.3164ZM8.125 14.375C4.67188 14.375 1.875 11.5781 1.875 8.125C1.875 4.67188 4.67188 1.875 8.125 1.875C11.5781 1.875 14.375 4.67188 14.375 8.125C14.375 11.5781 11.5781 14.375 8.125 14.375Z"></path></svg>
        </div>
        <div class="h-full w-full pr-2">
          <input type="search" name="search" @keyup="search" @search="search" v-model="query" placeholder="Search..."
                 class="bg-transparent h-full outline-none placeholder-gray-500 text-gray-700 text-sm w-64 pl-1">
        </div>
      </div>

      <!-- New resource button -->
      <router-link
          :to="{ name: 'resource-create', params: { resourceName: $route.params.resourceName } }"
          class="bg-blue-600 flex focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 h-full items-center px-3 rounded">
        <span class="text-base text-white">New {{ resources.labels.singular }}</span>
      </router-link>
    </div>

    <!-- Datatable -->
    <div class="bg-white flex flex-col max-w-0 min-w-full rounded-lg shadow w-full">
      <!-- Datatable tools -->
      <div class="flex items-center justify-between h-9 m-4">
        <!-- Tools left -->
        <div class="flex items-center space-x-4 h-full">
          <!-- Selection tool -->
          <div class="flex items-center px-2">
            <input type="checkbox" name="selectAll" v-model="selectAll" class="cursor-pointer h-4 w-4">
            <button class="ml-1 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <svg class="h-5 p-1 w-5" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"></path></svg>
            </button>
          </div>
        </div>

        <!-- Tools right -->
        <div class="flex items-center space-x-4 h-full">
          <!-- Actions tool -->
          <div class="flex h-full space-x-2" v-if="selectedResources.length">
            <div class="bg-white border border-gray-300 focus-within:border-blue-500 focus-within:outline-none focus-within:ring-blue-500 rounded-md shadow-sm pr-1.5">
              <select v-model="selectedAction" class="bg-transparent block h-full outline-none px-2 py-1 sm:text-sm text-gray-600 w-56">
                <option value="" hidden selected>Select an action</option>
                <option v-for="action in resources.actions" :value="action.key">{{ action.name }}</option>
                <option v-if="!resources?.actions.length" disabled>No actions available</option>
              </select>
            </div>
            <button @click="runSelectedAction"
                    class="bg-gray-200 h-full flex focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 items-center px-3 rounded text-gray-700">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M14.2629 6.7081L3.26256 0.20524C2.36879 -0.322864 1 0.189616 1 1.49581V14.4984C1 15.6702 2.27191 16.3765 3.26256 15.789L14.2629 9.28925C15.2441 8.71115 15.2473 7.2862 14.2629 6.7081V6.7081Z"/></svg>
            </button>
          </div>

          <!-- Reload tool -->
          <button class="bg-gray-200 h-full flex focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 items-center px-3 rounded text-gray-700"
                  @click="getResources">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.3392 0.660806L13.6565 2.34355C12.2089 0.89571 10.2092 0 8 0C3.71703 0 0.220129 3.36574 0.01 7.59655C-0.00093547 7.81648 0.176613 8 0.396806 8H1.30148C1.50642 8 1.6761 7.84026 1.68771 7.63568C1.87616 4.31126 4.62752 1.67742 8 1.67742C9.74719 1.67742 11.3276 2.38461 12.4714 3.52858L10.7254 5.27468C10.4815 5.51855 10.6542 5.93548 10.9991 5.93548H15.6129C15.8267 5.93548 16 5.76216 16 5.54839V0.934548C16 0.589677 15.583 0.416968 15.3392 0.660806V0.660806ZM15.6032 8H14.6985C14.4936 8 14.3239 8.15974 14.3123 8.36432C14.1238 11.6887 11.3725 14.3226 8 14.3226C6.25281 14.3226 4.67235 13.6154 3.52858 12.4714L5.27465 10.7253C5.51852 10.4815 5.34581 10.0645 5.00094 10.0645H0.387097C0.173323 10.0645 0 10.2378 0 10.4516V15.0655C0 15.4103 0.416968 15.583 0.660806 15.3392L2.34355 13.6565C3.79113 15.1043 5.79084 16 8 16C12.283 16 15.7799 12.6343 15.99 8.40345C16.0009 8.18352 15.8234 8 15.6032 8Z"/></svg>
          </button>

          <FilterMenu
              :per-page="resources.meta.per_page"
              :per-page-options="resources.perPageOptions"
              :soft-deletes="resources.softDeletes"
              @per-page-changed="updatePerPageChanged"
              @trashed-changed="trashedChanged"
          />
        </div>
      </div>

      <Loading :loading="loading">
        <template v-if="resources.data.length">
          <!-- Datatable entries -->
          <div class="overflow-hidden overflow-x-auto relative">
            <Datatable
                :resources="resources"
                :selected-resources="selectedResources"
                @order="orderByField"
                @select="selectResource"
                @delete="deleteResource"
                @restore="restoreResource"
            />
          </div>

          <!-- Datatable pagination -->
          <div class="flex justify-between p-2 items-center">
            <div>&nbsp;</div>
            <div class="flex items-center gap-x-4">
              <Pagination :meta="resources.meta" @changePage="changePage" />
            </div>
          </div>
        </template>
        <div v-else class="flex flex-col items-center justify-center pb-20 pt-10 text-gray-400">
          <svg class="w-16" viewBox="0 0 34 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3 0H29C30.6569 0 32 1.34312 32 3V16.0225C31.429 15.3456 30.7541 14.7591 30 14.2876V12H22V13.7517C21.273 14.0806 20.6006 14.5088 20 15.019V12H12V18H17.7517C17.4669 18.6295 17.2566 19.2999 17.1319 20H12V26H18.2876C18.7591 26.7541 19.3456 27.429 20.0225 28H3C1.34312 28 0 26.6569 0 25V3C0 1.34312 1.34312 0 3 0ZM3 26H10V20H2V25C2 25.5523 2.44769 26 3 26ZM2 18H10V12H2V18ZM2 10H10V4H2V10ZM12 10H20V4H12V10ZM22 10H30V4H22V10Z"/>
            <path d="M29.5 21.3125V21.6875C29.5 21.8937 29.3312 22.0625 29.125 22.0625H26.0625V25.125C26.0625 25.3312 25.8937 25.5 25.6875 25.5H25.3125C25.1063 25.5 24.9375 25.3312 24.9375 25.125V22.0625H21.875C21.6688 22.0625 21.5 21.8937 21.5 21.6875V21.3125C21.5 21.1063 21.6688 20.9375 21.875 20.9375H24.9375V17.875C24.9375 17.6688 25.1063 17.5 25.3125 17.5H25.6875C25.8937 17.5 26.0625 17.6688 26.0625 17.875V20.9375H29.125C29.3312 20.9375 29.5 21.1063 29.5 21.3125ZM33.25 21.5C33.25 25.7812 29.7812 29.25 25.5 29.25C21.2188 29.25 17.75 25.7812 17.75 21.5C17.75 17.2188 21.2188 13.75 25.5 13.75C29.7812 13.75 33.25 17.2188 33.25 21.5ZM32.25 21.5C32.25 17.7531 29.2094 14.75 25.5 14.75C21.7531 14.75 18.75 17.7906 18.75 21.5C18.75 25.2469 21.7906 28.25 25.5 28.25C29.2469 28.25 32.25 25.2094 32.25 21.5Z"/>
          </svg>
          <span class="mt-4 text-gray-500">No {{ resources.labels.plural.toLowerCase() }} matched the given criteria.</span>
          <!-- New resource button -->
          <router-link
              :to="{ name: 'resource-create', params: { resourceName: $route.params.resourceName } }"
              class="border border-blue-600 flex focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 h-full items-center px-3 py-1.5 rounded mt-4">
            <span class="text-base text-blue-600 font-medium">New {{ resources.labels.singular }}</span>
          </router-link>
        </div>
      </Loading>
    </div>
  </div>
</template>

<script>
import { debounce } from 'lodash'

export default {
  name: "Index",
  data() {
    return {
      loading: true,

      query: null,
      resources: {
        actions: [],
        data: [],
        header: [],
        labels: {},
        meta: {
          current_page: 1,
          from: 1,
          last_page: 1,
          per_page: 10,
          to: 1,
          total: 0,
        },
        perPageOptions: [25, 50, 100],
        softDeletes: false,
      },
      cards: [],

      // Selected resources
      selectedResources: [],
      selectedAction: '',
    }
  },
  mounted() {
    let query = new URLSearchParams({ ...this.$route.query })

    if (query.has('q')) this.query = query.get('q')

    this.getResources()
    this.getCards()
  },
  methods: {
    /**
     * Get the resources based on the current page, search, filters, etc.
     */
    getResources() {
      this.loading = true

      this.$nextTick(() => {
        this.selectedResources = []

        this.$http.get(`/resources/${this.$route.params.resourceName}`, {
          params: this.$route.query,
        }).then(({ data }) => {
          this.resources = data
          this.loading = false
        })
      })
    },

    /**
     * Get the cards for the current resource.
     */
    getCards() {
      this.$http.get(`/cards/${this.$route.params.resourceName}`)
          .then(({ data }) => this.cards = data)
    },

    /**
     * Select a resource.
     */
    selectResource(key) {
      if (this.selectedResources.includes(key)) {
        this.selectedResources = this.selectedResources.filter(r => r !== key)
      } else {
        this.selectedResources.push(key)
      }
    },

    /**
     * Order the resources by a given field.
     */
    orderByField(field) {
      if (!field.sortable) return null

      const URLSearch = new URLSearchParams(location.search)

      if (!URLSearch.has('sortBy') || !URLSearch.has('sortOrder')) {
        URLSearch.delete('sortBy')
        URLSearch.delete('sortOrder')
      }

      let sortBy = URLSearch.get('sortBy')
      sortBy = (sortBy) ? sortBy.split(',') : []

      let sortOrder = URLSearch.get('sortOrder')
      sortOrder = (sortOrder) ? sortOrder.split(',') : []

      let index = sortBy.findIndex(element => element === field.key)
      if (index !== -1) {
        let order = sortOrder[index]
        if (order === 'desc') {
          sortBy.splice(index, 1)
          sortOrder.splice(index, 1)
        } else {
          sortOrder[index] = 'desc'
        }
      } else {
        sortBy.push(field.key)
        sortOrder.push('asc')
      }

      this.$router.push({ query: { ...this.$route.query, sortBy: sortBy.join(','), sortOrder: sortOrder.join(',') }})
    },

    /**
     * Delete the specified resource.
     */
    deleteResource(key) {
      this.$http.delete(`/resources/${this.$route.params.resourceName}/${key}`)
          .then(() => {
            this.$notify({ type: 'success', title: 'Resource removed', text: 'The resource has been deleted correctly.', timeout: 4000 })

            this.getResources()
            this.getCards()
          })
    },

    /**
     * Restore the specified resource.
     */
    restoreResource(key) {
      this.$http.post(`/resources/${this.$route.params.resourceName}/${key}/restore`)
          .then(() => {
            this.$notify({ type: 'success', title: 'Resource restored', text: 'The resource has been restored correctly.', timeout: 4000 })

            this.getResources()
            this.getCards()
          })
    },

    /**
     * Search resources based on the current query.
     */
    search() {
      debounce(() => {
        this.$router.push({ query: { ...this.$route.query, q: this.query }})
      }, 1000)()
    },

    /**
     * Run the selected action on the selected resources.
     */
    runSelectedAction() {
      if (!this.selectedAction) return

      this.$notify({ type: 'success', title: 'Action requested', text: 'The action has been scheduled.', timeout: 2000 })

      this.axios.post(`/actions/${this.$route.params.resourceName}`, {
        action: this.selectedAction,
        models: this.selected,
      }).then(() => {
        this.$notify({ type: 'success', title: 'Action executed', text: 'The action has been executed successfully.', timeout: 4000 })
      })
    },

    /**
     * Update the trashed constraint for the resource listing.
     */
    trashedChanged(trashedStatus) {
      this.$router.push({ query: { ...this.$route.query, trashed: trashedStatus } })
    },

    /**
     * Update the per page parameter in the query string
     */
    updatePerPageChanged(perPage) {
      this.$router.push({ query: { ...this.$route.query, perPage: perPage }})
    },

    /**
     * Change the page in the query string
     */
    changePage(page) {
      this.$router.push({ query: { ...this.$route.query, page: page }})
    },
  },
  computed: {
    selectAll: {
      get() {
        if (!this.resources.data.length) return false

        return this.selectedResources.length === this.resources.data.length
      },
      set(value) {
        let selected = []
        if (value) this.resources.data.forEach(resource => selected.push(resource.key))
        this.selectedResources = selected
      }
    }
  },
  watch: {
    '$route.query': {
      handler() {
        if (this.$route.name !== 'resource-index') return

        this.getResources()
      },
      deep: true
    }
  }
}
</script>
