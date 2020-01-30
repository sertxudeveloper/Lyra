<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-4 px-lg-5">

    <div class="align-items-baseline d-flex justify-content-between">
      <div>
        <h3 class="pb-3">{{ resource.labels.singular }} details</h3>
      </div>
      <div class="mb-2 text-right">
        <div class="btn-group" role="group" aria-label="Languages available">
          <button type="button" class="btn" :class="languagesClass(language, key)"
                  @click="changeLanguage(language)"
                  v-for="(language, key) in resource.languages">{{language.toUpperCase()}}
          </button>
        </div>
        <a href="#" v-on:click="recoverItem(resource.collection.data[0])"
           v-if="getPrimaryField(resource.collection.data[0]).soft_deleted"
           class="bg-white box-dark-shadow btn btn-light text-body" title="Recover">
          <i class="fas fa-undo"></i>
        </a>
        <a href="#" v-on:click="removeItem(resource.collection.data[0])" v-else
           class="bg-white box-dark-shadow btn btn-light text-body" title="Delete">
          <i class="fas fa-trash-alt"></i>
        </a>
        <a href="#" v-on:click="forceRemoveItem(resource.collection.data[0])" v-if="resource.collection.hasSoftDeletes"
           class="bg-white box-dark-shadow btn btn-light text-body" title="Force Delete">
          <i class="fas fa-trash"></i>
        </a>
        <router-link
          :to="{ name: 'edit', params: { resourceName: getResourceName(), resourceId: getPrimaryField(resource.collection.data[0]).value }, query: { lang: $route.query.lang }}"
          v-if="!getPrimaryField(resource.collection.data[0]).soft_deleted"
          class="btn btn-primary text-white" title="Edit">
          <i class="fas fa-pencil-alt"></i>
        </router-link>
      </div>
    </div>

    <div class="align-items-baseline d-flex justify-content-between">
      <div class="panel box-dark-shadow col-12 py-2 w-100">
        <div v-for="field in resource.collection.data[0]"
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
                   v-if="field.component === 'morph-one-to-one-field' && !isRelationshipField(field.component)"
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
  import BelongsToManyInverseField from '../../Fields/Show/BelongsToManyInverseField'
  import MorphOneToOneField from '../../Fields/Show/MorphOneToOneField'

  export default {
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
        return (component === 'has-many-field' || component === 'belongs-to-many-inverse-field')
      },
      isHeadingField: function (component) {
        return component === 'heading-field';
      },
      removeItem: function (collection) {
        this.$http.post(`${this.getRoute()}/delete`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} deleted successfully`);
            this.$router.go()
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
      recoverItem: function (collection) {
        this.$http.post(`${this.getRoute()}/recover`).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} #${this.getPrimaryField(collection).value} recovered successfully`);
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
    components: {HasManyField, BelongsToManyInverseField, MorphOneToOneField},
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
