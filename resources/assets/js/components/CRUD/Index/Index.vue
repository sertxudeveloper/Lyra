<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-4 px-lg-5">
    <h3 class="pb-3">{{ resource.labels.plural }}</h3>
    <div class="align-items-baseline d-flex justify-content-between" v-if="resource.labels.plural !== null">
      <div>
        <div class="input-group mb-3 box-dark-shadow">
          <div class="input-group-prepend">
            <span class="input-group-text border-0" id="basic-addon1"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" class="form-control border-0" v-model="search" placeholder="Search">
        </div>
      </div>
      <div>
        <div class="btn-group" role="group" aria-label="Languages available">
          <button type="button" class="btn" :class="languagesClass(language, key)"
                  @click="changeLanguage(language)"
                  v-for="(language, key) in resource.languages">{{language.toUpperCase()}}
          </button>
        </div>
        <router-link :to="{ name: 'create'}" append class="btn btn-primary py-1">
          Create {{ resource.labels.singular }}
        </router-link>
      </div>
    </div>
    <div>
      <data-table :resource="resource" @get-resource="getResource" @clear-resource="clearResource"/>
    </div>
  </div>
</template>

<script>
  import DataTable from './Datatable'

  export default {
    components: {DataTable},
    data() {
      return {
        resource: null,
        search: this.$route.query.search ? this.$route.query.search : null,
        isTyping: null,
      }
    },
    methods: {
      getResource: function () {
        this.$http.get(this.$route.fullPath).then((response) => this.resource = response.data);
      },
      clearResource: function () {
        this.resource.collection = {data: null}
      },
      changeLanguage: function (lang) {
        this.$router.push({query: {...this.$route.query, lang}});
        this.getResource();
      },
      languagesClass: function (lang, key) {
        return {
          'btn-secondary': (!this.$route.query.lang && key === 0) || (this.$route.query.lang && this.$route.query.lang === lang),
          'btn-outline-dark': (!this.$route.query.lang && key !== 0) || (this.$route.query.lang && this.$route.query.lang !== lang)
        }
      }
    },
    watch: {
      search: function () {
        clearTimeout(this.isTyping);

        this.isTyping = setTimeout(() => {
          delete this.$route.query.page;
          this.$router.push({query: {...this.$route.query, search: this.search}});
          this.getResource();
          // this.$http.get(this.$route.fullPath).then((response) => this.resource = response.data);
        }, 500);
      }
    },
    beforeMount: function () {
      this.$root.enableLoader();
      this.getResource();
      // this.$http.get(this.$route.fullPath).then((response) => this.resource = response.data);
    }
  }
</script>

<style scoped>
  span.input-group-text {
    background-color: #fff;
  }

  .form-control,
  .input-group,
  .input-group-prepend > span {
    border-radius: 10px;
  }

  .form-control:focus {
    border-color: lightgray;
    box-shadow: initial;
  }
</style>
