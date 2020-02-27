<template>
  <div class="col form-inline h-100 pl-0 pl-sm-3 pr-0 dropdown" id="searchResults">
    <div class="align-items-center d-flex form-group h-100 m-0 w-100">
      <i class="d-none d-sm-block fa-search fas"></i>
      <input type="text" id="search" class="form-control h-100 m-0" name="search" autofocus
             placeholder="Search models, actions or help" ref="search" v-model="search" @focus="openResults">
    </div>

    <button id="dropdownMenuSearch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="d-none"></button>

    <div class="dropdown-menu rounded-0 p-0" aria-labelledby="dropdownMenuSearch">
      <div class="bg-light shadow search-results">
        <div v-if="!searchResults.length" class="align-items-center d-flex h-100 justify-content-center">
          <span>{{search}} not found</span>
        </div>
        <template v-else>
          <div v-for="resource in searchResults">
            <div class="px-3 py-1 group-title">{{ resource.name.toUpperCase() }} - {{ resource.results.length }} found
            </div>
            <div>
              <router-link v-for="result in resource.results" class="result-element"
                           :key="resource.key + '-' + result.primary" @click.native="closeResults"
                           :to="{name: 'show', params: {resourceName: resource.key, resourceId: result.primary}}">
                <div class="px-3 py-2 d-flex flex-column">
                  <span>{{ result.title }}</span>
                  <small v-show="result.subtitle">{{ result.subtitle }}</small>
                </div>
              </router-link>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        search: null,
        searchResults: [],
        isTyping: null
      }
    },
    methods: {
      clearSearch: function () {
        this.search = null;
      },
      clearSearchResults: function () {
        this.searchResults = [];
        this.closeResults();
      },
      openResults: function () {
        if (this.search) $('#dropdownMenuSearch').dropdown('show');
      },
      closeResults: function () {
        $('#dropdownMenuSearch').dropdown('hide');
      }
    },
    watch: {
      search: function () {
        clearTimeout(this.isTyping);

        if (!this.searchResults.length) this.closeResults();

        this.isTyping = setTimeout(() => {
          if (!this.search || this.search.trim().length === 0) {
            this.clearSearchResults();
            return null;
          }

          this.$http.get('/search?q=' + this.search).then((response) => {
            this.searchResults = response.data;
            this.openResults();
          });
        }, 500);
      }
    },
    mounted() {
      window.addEventListener("keydown", e => {
        if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
          e.preventDefault();
          this.$refs.search.focus();
        }
      })
    }
  }
</script>

<style scoped>
  .search-results {
    width: 350px;
    height: 300px;
    overflow-y: auto;
  }

  .search-results .group-title {
    background: #e6e6e6;
    font-size: 11px;
    font-weight: bold;
    color: #4a4a4a;
  }

  .result-element {
    outline: none !important;
    text-decoration: none !important;
    color: #000;
  }

  .result-element:hover > div {
    background: #f0f0f0;
  }
</style>
