<template>
  <div class="table-container box-dark-shadow">
    <div v-if="resource.collection.data.length > 0 && resource.labels.plural !== null && $root.loader === false">
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
            <form v-on:change="formOnChange()">
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
                        <option>5</option>
                        <option>15</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                        <option>200</option>
                      </select>
                    </div>
                  </div>

                  <div>
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
      <div class="table-responsive">
        <table class="table mb-0">
          <thead class="thead-dark">
          <tr>
            <th class="select-checkbox"></th>
            <th scope="col" v-for="field in resource.collection.data[0]">{{ field.name }}</th>
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
              <component :is="field.component" :field="field"></component>
            </td>
            <td class="p-0 actions">
              <router-link :to="{ name: 'show', params: { resourceId: getPrimaryField(collection).value }}" append
                           class="btn btn-link text-body" title="Show">
                <i class="fas fa-eye"></i>
              </router-link>
              <router-link :to="{ name: 'edit', params: { resourceId: getPrimaryField(collection).value }}" append
                           class="btn btn-link text-body" title="Edit">
                <i class="fas fa-pencil-alt"></i>
              </router-link>
              <a href="#" v-on:click="recoverItem(collection)" v-if="getPrimaryField(collection).softDeleted"
                 class="btn btn-link text-body" title="Recover">
                <i class="fas fa-undo"></i>
              </a>
              <a href="#" v-on:click="restoreItem(collection)" v-else class="btn btn-link text-body" title="Delete">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="align-items-center d-flex justify-content-between px-4 w-100 pagination-container">
        <div>
          <small>
            Showing {{resource.collection.data.length}} of {{resource.collection.total}}
            {{resource.labels.plural.toLowerCase()}}
          </small>
        </div>
        <pagination v-if="resource.collection.last_page > 1" :pagination="resource.collection" :offset="5"></pagination>
      </div>
    </div>
    <div v-if="resource.collection.data.length === 0 && resource.labels.plural !== null && $root.loader === false">
      <div class="py-5 text-center">No data available</div>
    </div>
  </div>
</template>

<script>
  import Pagination from './Pagination'

  import IdField from '../../Fields/Read/IdField'
  import TextField from '../../Fields/Read/TextField'
  import BelongsToField from '../../Fields/Read/BelongsToField'

  export default {
    props: ['resource'],
    components: {Pagination, IdField, TextField, BelongsToField},
    data() {
      return {
        perPage: (this.$route.query.perPage) ? this.$route.query.perPage : 25,
        visibility: (this.$route.query.visibility) ? this.$route.query.visibility : 'default',
        selected: [],
      }
    },
    methods: {
      formOnChange: function () {
        this.$router.push({query: {...this.$route.query, perPage: this.perPage, page: 1, visibility: this.visibility}});
        this.$http.get(this.$route.fullPath).then((response) => this.$parent.resource = response.data);
      },
      selectItem: function (item, event) {
        if (event.target.checked === true) {
          this.selected.push(item);
          if (this.selected.length === this.resource.collection.data.length) this.allSelected = true;
        } else {
          if (this.selected.length === this.resource.collection.data.length) this.allSelected = false;
          let index = this.selected.indexOf(item);
          this.selected.splice(index, 1);
        }
      },
      removeItem: function (collection) {
        console.log(collection)
      },
      getPrimaryField: function (collection) {
        return collection.find(function (field) {
          if (field.primary === true) {
            return field
          }
        });
      }
    },
    computed: {
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
  .table-container {
    border-radius: 10px;
    background-color: #fff;
  }

  .table-container .header {
    min-height: 50px;
  }

  .table-container .checkbox-container {
    width: 20px;
    height: 20px;
  }

  .table-container .pagination-container {
    border-radius: 0;
    min-height: 40px;
    border-top: 1px solid;
  }

  .header .select-checkbox {
    width: 40px;
    text-align: center;
  }

  .dropdown-menu {
    min-width: 300px;
  }

  .dropdown-header {
    padding: 7px 15px;
    color: #6c757d;
    background-color: rgba(0, 0, 0, 0.07);
    font-size: 15px;
    font-weight: bold;
  }

  form {
    height: 38px;
  }

  td.actions {
    width: 140px;
    vertical-align: middle;
  }

  tr:hover {
    background-color: var(--background);
  }

  .select-checkbox {
    width: 20px;
  }
</style>