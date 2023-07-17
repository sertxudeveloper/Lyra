<template>
  <!-- Resource Index -->
  <div class="xl:px-4 xl:pb-4">
    <h1 class="capitalize mb-4 text-2xl text-gray-800">{{ resources.labels?.plural ?? "&nbsp;" }}</h1>

    <!-- Cards -->
    <div v-if="cards.length"
         class="gap-4 grid grid-cols-1 md:grid-cols-2 mb-4 xl:grid-cols-4">
      <Component v-for="card in cards"
                 :is="card.component"
                 :card="card" />
    </div>

    <!-- Toolbar row -->
    <div class="flex items-center justify-between h-9 my-4">
      <!-- Search tool -->
      <div class="bg-white border border-gray-300 flex h-10 items-center rounded-md text-gray-500 w-72">
        <div class="flex-shrink-0 h-4 ml-3 mr-2 w-4">
            <Icon name="lens" class="w-4" />
        </div>
        <input type="search" name="search" placeholder="Search..."
               @keyup="search" @search="search" v-model="query"
               class="form-input border-0 h-full pl-2">
      </div>

      <!-- New resource button -->
      <RouterLink
          v-if="resources.labels.singular" class="btn-primary"
          :to="{ name: 'resource-create', params: { resourceName: $route.params.resourceName } }">
          <span>New {{ resources.labels.singular }}</span>
      </RouterLink>
    </div>

    <!-- Datatable -->
    <div class="bg-white flex flex-col max-w-0 min-w-full rounded-lg shadow w-full">
      <!-- Datatable tools -->
      <div class="flex items-center justify-between h-9 m-4">
        <!-- Tools left -->
        <div class="flex items-center space-x-4 h-full">
          <!-- Selection tool -->
          <div class="flex items-center px-2">
            <input type="checkbox" name="selectAll" v-model="selectAll"
                   class="cursor-pointer w-4 h-4 text-blue-600 border-gray-400 form-checkbox">
          </div>
        </div>

        <!-- Tools right -->
        <div class="flex items-center space-x-4 h-full">
          <!-- Actions tool -->
          <div class="flex h-full space-x-2 w-72" v-if="selectedResources.length">
            <select v-model="selectedAction" class="form-select">
              <option value="" hidden selected>Select an action</option>
              <option v-for="action in resources.actions"
                      :value="action.key">{{ action.name }}</option>
              <option v-if="!resources?.actions.length" disabled>No actions available</option>
            </select>

            <button @click="runSelectedAction"
                    class="bg-gray-200 focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 px-3 rounded text-gray-700">
                <Icon name="play" class="w-4" />
            </button>
          </div>

          <!-- Reload tool -->
          <button class="btn-toolbar" @click="getResources">
              <Icon name="reload" class="w-4" />
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

      <Loading :loading="loading" class="pb-32 pt-24">
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
          <Icon name="table-add" class="w-16" />
          <span class="mt-4 text-gray-500">No {{ resources.labels.plural.toLowerCase() }} matched the given criteria.</span>
          <!-- New resource button -->
          <RouterLink
              :to="{ name: 'resource-create', params: { resourceName: $route.params.resourceName } }"
              class="border border-blue-600 flex focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 h-full items-center px-3 py-1.5 rounded mt-4">
            <span class="text-sm text-blue-600 font-medium">New {{ resources.labels.singular }}</span>
          </RouterLink>
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
