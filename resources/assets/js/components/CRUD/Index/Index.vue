<template>
  <div v-if="$root.loader === false && resource !== null">
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
      <router-link :to="{ name: 'create'}" append class="btn btn-primary py-1">
        Create {{ resource.labels.singular }}
      </router-link>
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
