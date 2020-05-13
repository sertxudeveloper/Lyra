<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-lg-4 px-lg-5 pt-3 px-3">

    <div class="align-items-baseline d-flex flex-wrap justify-content-between">
      <div>
        <h3 class="pb-3">{{ resource.labels.singular }} details</h3>
      </div>
      <div class="mb-2 text-right ml-auto">
        <div class="btn-group" role="group" aria-label="Languages available">
          <button type="button" class="btn" :class="languagesClass(language, key)"
                  @click="changeLanguage(language)"
                  v-for="(language, key) in resource.languages">{{language.toUpperCase()}}
          </button>
        </div>

        <template v-if="!getPrimaryField(resource.collection.data[0]).trashed">
          <a @click="editItem(resource.collection.data[0])" class="btn btn-primary text-white box-dark-shadow"
             :class="{'disabled': !resource.permissions.delete}" title="Edit">
            <i class="fas fa-pencil-alt"></i>
          </a>
          <a @click="removeItem(resource.collection.data[0])" class="bg-white box-dark-shadow btn btn-light text-body"
             :class="{'disabled': !resource.permissions.delete}" title="Delete">
            <i class="fas fa-trash-alt"></i>
          </a>
          <a @click="forceRemoveItem(resource.collection.data[0])" v-if="resource.hasSoftDeletes"
             class="bg-white box-dark-shadow btn btn-light text-body"
             :class="{'disabled': !resource.permissions.delete}" title="Force Delete">
            <i class="fas fa-trash"></i>
          </a>
        </template>

        <template v-else>
          <a @click="restoreItem(resource.collection.data[0])" class="bg-white box-dark-shadow btn btn-light text-body"
             :class="{'disabled': !resource.permissions.delete}" title="Restore">
            <i class="fas fa-undo"></i>
          </a>
          <a @click="forceRemoveItem(resource.collection.data[0])"
             class="bg-white box-dark-shadow btn btn-light text-body"
             :class="{'disabled': !resource.permissions.delete}" title="Force Delete">
            <i class="fas fa-trash"></i>
          </a>
        </template>
      </div>
    </div>

    <div class="align-items-baseline d-flex justify-content-between">
      <div class="panel box-dark-shadow col-12 py-2 w-100">
        <div v-for="field in resource.collection.data[0]" :key="field"
             v-if="!isRelationshipField(field.component) && field.component !== 'morph-one-to-one-field'"
             class="row field-row py-2 align-items-center"
             :class="[!isHeadingField(field.component) ? 'mx-0' : 'heading-field']">

          <template v-if="!isHeadingField(field.component)">
            <div class="col-3 mb-1 mb-lg-0 text-muted">
              <span>{{ field.name }} <i class="fas fa-language" v-if="field.translatable"></i></span><br>
              <small>{{ field.description }}</small>
            </div>
            <div class="col-12 col-md-12 col-lg-9 col-xl-7">
              <component :is="`${field.component}-readable`" :field="field"></component>
            </div>
          </template>

          <template v-else>
            <div class="col-12">
              <div v-html="field.value"></div>
            </div>
          </template>

        </div>

        <component v-for="field in resource.collection.data[0]"
                   v-if="isMorphField(field.component) && field.value.length"
                   :is="field.component" :key="field.column" :resource.sync="field.value"></component>
      </div>
    </div>

    <component v-for="field in resource.collection.data[0]" v-if="isRelationshipField(field.component)"
               :is="field.component" :key="field.path" :resource.sync="field.value" :path="field.path"
               :foreign_column="field.foreign_column"></component>
  </div>
</template>

<script>
  import HasManyField from '../../Fields/Show/HasManyField'
  import BelongsToManyTableField from '../../Fields/Show/BelongsToManyTableField'
  import MorphOneToOneField from '../../Fields/Show/MorphOneToOneField'

  export default {
    components: {HasManyField, BelongsToManyTableField, MorphOneToOneField},
    data() {
      return {
        resource: null,
      }
    },
    methods: {
      getResource: function () {
        this.$root.enableLoader();
        this.$http.get(this.$route.fullPath).then((response) => this.resource = response.data);
      },
      isRelationshipField: function (component) {
        return (component === 'has-many-field' || component === 'belongs-to-many-table-field')
      },
      isMorphField: function (component) {
        return (component === 'morph-one-to-one-field')
      },
      isHeadingField: function (component) {
        return component === 'heading-field';
      },
      editItem: function (collection) {
        if (!this.resource.permissions.write) return toastr.error("You're not allowed to edit this resource");
        this.$router.push({
          name: 'edit',
          params: {resourceName: this.getResourceName(), resourceId: this.getPrimaryField(collection).value},
          query: {lang: this.$route.query.lang}
        });
      },
      removeItem: function (collection) {
        if (!this.resource.permissions.delete) return toastr.error("You're not allowed to delete this resource");
        this.$http.post(`${this.getRoute()}/delete`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} deleted successfully`);
            if (!this.resource.hasSoftDeletes) {
              this.getResource();
            } else {
              this.$router.go(-1);
            }
          }
        })
      },
      forceRemoveItem: function (collection) {
        if (!this.resource.permissions.delete) return toastr.error("You're not allowed to force delete this resource");
        this.$http.post(`${this.getRoute()}/forceDelete`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} deleted successfully`);
            this.$router.go(-1);
          }
        })
      },
      restoreItem: function (collection) {
        if (!this.resource.permissions.delete) return toastr.error("You're not allowed to restore this resource");
        this.$http.post(`${this.getRoute()}/restore`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} restored successfully`);
            this.getResource();
          }
        })
      },
      getPrimaryField: function (collection) {
        return collection.find(field => {
            if (field.primary === true) return field
          }
        );
      },
      getResourceName: function () {
        return (!this.path) ? this.$route.params.resourceName : this.path
      },
      getRoute: function () {
        return (!this.path) ? this.$route.path : `/${this.path}`
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
    beforeMount: function () {
      this.getResource();
    },
    updated() {
      $('.heading-field').prev().addClass("border-0");
    }
  }
</script>

<style scoped>

</style>
