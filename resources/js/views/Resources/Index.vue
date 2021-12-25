<template>
  <div class="xl:px-4 xl:pb-4" v-if="resources?.data">
    <h1 class="capitalize mb-4 text-2xl text-gray-800">{{ resources.labels.plural }}</h1>
    <div v-if="cards.length" class="gap-4 grid grid-cols-1 md:grid-cols-2 mb-4 xl:grid-cols-4">
      <component v-for="card in cards" :is="card.component" :card="card"></component>
    </div>

    <!-- Toolbar row -->
    <div class="flex items-center justify-between h-9 my-4">
      <!-- Search tool -->
      <div class="bg-white border border-gray-300 flex h-10 items-center rounded-md text-gray-500">
        <div class="flex-shrink-0 h-4 mx-2 w-4">
          <svg height="16" width="16" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.8633 18.3164L15.1211 13.5742C15.0312 13.4844 14.9141 13.4375 14.7891 13.4375H14.2734C15.5039 12.0117 16.25 10.1562 16.25 8.125C16.25 3.63672 12.6133 0 8.125 0C3.63672 0 0 3.63672 0 8.125C0 12.6133 3.63672 16.25 8.125 16.25C10.1562 16.25 12.0117 15.5039 13.4375 14.2734V14.7891C13.4375 14.9141 13.4883 15.0312 13.5742 15.1211L18.3164 19.8633C18.5 20.0469 18.7969 20.0469 18.9805 19.8633L19.8633 18.9805C20.0469 18.7969 20.0469 18.5 19.8633 18.3164ZM8.125 14.375C4.67188 14.375 1.875 11.5781 1.875 8.125C1.875 4.67188 4.67188 1.875 8.125 1.875C11.5781 1.875 14.375 4.67188 14.375 8.125C14.375 11.5781 11.5781 14.375 8.125 14.375Z"></path></svg>
        </div>
        <div class="h-full w-full pr-1">
          <input type="search" name="search" @keyup="search" @search="search" v-model="query" placeholder="Search..."
                 class="bg-transparent h-full outline-none placeholder-gray-500 text-gray-700 text-sm w-64">
        </div>
      </div>

      <!-- New resource button -->
      <router-link :to="{ name: 'resource-create', params: { resourceName: $route.params.resourceName } }"
                   class="bg-blue-600 flex focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 h-full items-center px-3 rounded">
        <span class="text-base text-white">New {{ resources.labels.singular }}</span>
      </router-link>
    </div>

    <Datatable :resources="resources" @updated="getResources" />

  </div>
</template>

<script>
import { debounce } from 'lodash'
import Datatable from "../../components/Resources/elements/Datatable";

export default {
  name: "Index",
  components: {Datatable},
  data() {
    return {
      query: null,
      resources: {},
      cards: [],
    }
  },
  mounted() {
    let query = new URLSearchParams({ ...this.$route.query })

    if (query.has('q')) this.query = query.get('q')

    this.getResources()
    this.getCards()
  },
  methods: {
    getResources(options = {}) {
      let query = new URLSearchParams({ ...this.$route.query, ...options })

      this.$http.get(`/resources/${this.$route.params.resourceName}?${decodeURIComponent(query.toString())}`)
          .then(response => {
            if (options.notify) this.$notify({ type: 'success', title: 'Table reloaded', text: 'The table is showing the last updated data.', timeout: 2000 })
            this.resources = response.data

            this.$router.push({ query: { ...this.$route.query, perPage: this.resources.meta.per_page, page: this.resources.meta.current_page }})
          })
    },
    getCards() {
      this.$http.get(`/cards/${this.$route.params.resourceName}`)
          .then(response => this.cards = response.data)
    },
    remove(index) {
      this.$http.delete(`/resources/${this.$route.params.resourceName}/${index}`)
          .then(response => {
            this.$notify({ type: 'success', title: 'Resource removed', text: 'The resource has been deleted correctly.', timeout: 4000 })
            this.getResources()
          })
    },
    search: debounce(function() {
      this.$router.push({ query: { ...this.$route.query, q: this.query }})
      this.getResources({ q: this.query })
    }, 1000)
  }
}
</script>
