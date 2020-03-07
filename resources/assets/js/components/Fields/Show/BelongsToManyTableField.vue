<template>

  <div class="mt-5" v-if="$root.loader === false && resource !== null">
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
      <router-link
        :to="{ name: 'create', params: { resourceName: this.path }, query: { [this.foreign_column]: $route.params.resourceId } }"
        class="btn btn-primary py-1">
        Create {{ resource.labels.singular }}
      </router-link>
    </div>
    <div>
      <data-table :resource="resource" :path="this.path" @get-resource="getResource" @clear-resource="clearResource"/>
    </div>
  </div>

</template>

<script>
  import DataTable from '../../CRUD/Index/Datatable'

  export default {
    props: ['resource', 'path', 'foreign_column'],
    components: {DataTable},
    data() {
      return {
        search: this.$route.query.search ? this.$route.query.search : null,
        isTyping: null,
      }
    },
    methods: {
      getResource: function () {
        this.$http.get(this.$route.fullPath).then((response) => {
          response.data.collection.data[0].find((field) => {
            if (field.path === this.path) this.$emit('update:resource', field.value);
          });
        });
      },
      clearResource: function () {
        this.resource.collection = {data: null};
        this.$emit('update:resource', this.resource)
      }
    },
    watch: {
      search: function () {
        clearTimeout(this.isTyping);

        this.isTyping = setTimeout(() => {
          delete this.$route.query.page;
          this.$router.push({query: {...this.$route.query, search: this.search}});
          this.getResource();
        }, 500);
      },
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
