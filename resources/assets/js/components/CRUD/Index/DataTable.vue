<template>
  <div class="table-container box-dark-shadow">
    <div v-if="resource.collection.data !== null && resource.labels.plural !== null && $root.loader === false">
      <div class="header d-flex justify-content-between align-items-center">
        <div class="h-100">
          <div class="align-items-center d-inline-flex h-100 justify-content-center select-checkbox">
            <label class="checkbox-container pl-0">
              <input type="checkbox" v-model="allSelected">
              <span class="checkmark"></span>
            </label>
          </div>
        </div>
        <div class="pr-3">
          <div class="d-flex">
            <button type="button" class="btn btn-outline-danger mx-3" v-if="selected.length > 0">
              <i class="fas fa-trash-alt"></i>
            </button>
            <form v-on:change="formOnChange()" class="filter-form">
              <div class="dropdown h-100">
                <button class="btn btn-outline-dark dropdown-toggle py-1 h-100" type="button" id="dropdownFilter"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right py-0 my-0 mt-1" aria-labelledby="dropdownFilter">

                  <div>
                    <span class="dropdown-header">Items per page</span>
                    <div class="form-group mx-3 my-2">
                      <select class="form-control" title="Items per page" v-model="perPage">
                        <option v-for="option in resource.perPageOptions">{{option}}</option>
                      </select>
                    </div>
                  </div>

                  <div v-if="resource.collection.hasSoftDeletes">
                    <span class="dropdown-header">Visibility</span>
                    <div class="form-group mx-3 my-2">
                      <select class="form-control" title="Visibility" v-model="visibility">
                        <option value="default">Default</option>
                        <option value="trashed">Only trashed</option>
                        <option value="all">All</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>

            </form>
          </div>
        </div>

      </div>
      <div class="table-responsive" v-if="!isNoDataAvailable">
        <table class="table mb-0">
          <thead class="thead-dark">
          <tr>
            <th class="select-checkbox"></th>
            <th scope="col" v-for="field in resource.collection.data[0]"
                @click="field.sortable && sortField(field.column)">
              {{ field.name }}
              <template v-if="field.sortable">
                <i class="fas fa-sort-down" v-if="sortClass(field.column, 'desc')"></i>
                <i class="fas fa-sort-up" v-if="sortClass(field.column, 'asc')"></i>
                <i class="fas fa-sort" v-if="sortClass(field.column, 'none')"></i>
              </template>
            </th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="collection in resource.collection.data">
            <td class="select-checkbox text-center">
              <div class="align-items-center d-inline-flex h-100 justify-content-center select-checkbox">
                <label class="checkbox-container pl-0">
                  <input type="checkbox" :value="collection" v-model="selected">
                  <span class="checkmark"></span>
                </label>
              </div>
            </td>
            <td v-for="field in collection">
              <component :is="`${field.component}-readable`" :field="field"></component>
            </td>
            <td class="p-0 pr-1 text-right actions">
              <router-link
                :to="{ name: 'show', params: { resourceName: getResourceName(), resourceId: getPrimaryField(collection).value }, query: { lang: $route.query.lang }}"
                class="btn btn-link text-body" title="Show">
                <i class="fas fa-eye"></i>
              </router-link>
              <template v-if="!getPrimaryField(collection).trashed">
                <router-link
                  :to="{ name: 'edit', params: { resourceName: getResourceName(), resourceId: getPrimaryField(collection).value }, query: { lang: $route.query.lang }}"
                  class="btn btn-link text-body" title="Edit">
                  <i class="fas fa-pencil-alt"></i>
                </router-link>
                <a href="#" v-on:click="removeItem(collection)" class="btn btn-link text-body" title="Delete">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </template>
              <template v-else>
                <a href="#" v-on:click="restoreItem(collection)" class="btn btn-link text-body" title="Restore">
                  <i class="fas fa-undo"></i>
                </a>
                <a href="#" v-on:click="forceRemoveItem(collection)" class="btn btn-link text-body" title="Force Delete">
                  <i class="fas fa-trash"></i>
                </a>
              </template>
            </td>
          </tr>
          </tbody>
        </table>
      </div>

      <div v-else>
        <div class="py-5 text-center">No data available</div>
      </div>

      <div class="align-items-center d-flex justify-content-between px-4 w-100 pagination-container">
        <div>
          <small>
            Showing {{resource.collection.data.length}} of {{resource.collection.total}}
            {{resource.labels.plural.toLowerCase()}}
          </small>
        </div>
        <pagination v-if="resource.collection.last_page > 1" :pagination="resource.collection" :offset="5"
                    @get-resource="getResourceEmit" @clear-resource="clearResourceEmit"></pagination>
      </div>
    </div>
    <lyra-loader v-if="isLoaderEnabled" class="py-5"></lyra-loader>
  </div>
</template>

<script>
  import Loader from '../../Loader'
  import Pagination from './Pagination'

  export default {
    props: ['resource', 'path'],
    components: {Loader, Pagination},
    data() {
      return {
        perPage: (this.$route.query.perPage) ? this.$route.query.perPage : this.resource.perPageOptions[0],
        visibility: (this.$route.query.visibility) ? this.$route.query.visibility : 'default',
        selected: [],
        currentSortCol: [],
        currentSortDir: []
      }
    },
    methods: {
      getResourceEmit: function () {
        this.$emit('get-resource');
      },
      clearResourceEmit: function () {
        this.$emit('clear-resource');
      },
      formOnChange: function () {
        this.$router.push({query: {...this.$route.query, perPage: this.perPage, page: 1, visibility: this.visibility}});
        this.$emit('clear-resource');
        this.$emit('get-resource');
      },
      removeSelectedItems: function () {
        this.selected.forEach(collection => this.removeItem(collection));
        this.selected = [];
      },
      removeItem: function (collection) {
        this.$http.post(`${this.getRoute()}/${this.getPrimaryField(collection).value}/delete`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} deleted successfully`);
            this.$emit('clear-resource');
            this.$emit('get-resource');
          }
        })
      },
      restoreItem: function (collection) {
        this.$http.post(`${this.getRoute()}/${this.getPrimaryField(collection).value}/restore`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} restored successfully`);
            this.$emit('clear-resource');
            this.$emit('get-resource');
          }
        })
      },
      forceRemoveItem: function (collection) {
        this.$http.post(`${this.getRoute()}/forceDelete`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} deleted successfully`);
            this.$router.go()
          }
        })
      },
      getPrimaryField: function (collection) {
        return collection.find(field => {
            if (field.primary === true) return field
          }
        );
      },
      getRoute: function () {
        return (!this.path) ? this.$route.path : `/${this.path}`
      },
      getResourceName: function () {
        return (!this.path) ? this.$route.params.resourceName : this.path
      },
      sortField: function (fieldColumn) {
        let sortIndex = this.currentSortCol.indexOf(fieldColumn);
        if (sortIndex === -1) this.currentSortCol.push(fieldColumn);
        switch (this.currentSortDir[sortIndex]) {
          case 'asc':
            this.currentSortDir[sortIndex] = 'desc';
            break;
          case 'desc':
            this.currentSortCol.splice(sortIndex, 1);
            this.currentSortDir.splice(sortIndex, 1);
            break;
          default:
            this.currentSortDir.push('asc');
            break;
        }
        let sortCol = this.currentSortCol.join(',');
        let sortDir = this.currentSortDir.join(',');
        this.$router.push({query: {...this.$route.query, sortCol, sortDir, page: 1, visibility: this.visibility}});
        this.$emit('clear-resource');
        this.$emit('get-resource');
      },
      sortClass: function (fieldColumn, sort) {
        let sortIndex = this.currentSortCol.indexOf(fieldColumn);
        if (sortIndex === -1 && sort === 'none') return true;
        return (sort === this.currentSortDir[sortIndex]);
      }
    },
    computed: {
      isLoaderEnabled: function () {
        return (this.$root.loader === false && this.resource.collection.data === null && this.resource.labels.plural !== null)
      },
      isNoDataAvailable: function () {
        return (this.$root.loader === false && this.resource.collection.data !== null && this.resource.collection.data.length === 0 && this.resource.labels.plural !== null)
      },
      allSelected: {
        get: function () {
          return this.resource.collection.data ? this.selected.length === this.resource.collection.data.length : false;
        },
        set: function (value) {
          let selected = [];

          if (value) {
            this.resource.collection.data.forEach(function (item) {
              selected.push(item);
            });
          }

          this.selected = selected;
        }
      }
    }
  }
</script>

<style scoped>

</style>
