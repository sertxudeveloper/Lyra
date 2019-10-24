<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-4 px-lg-5">

    <div class="align-items-baseline d-flex justify-content-between">
      <div>
        <h3 class="pb-3">Edit {{ resource.labels.singular.toLowerCase() }}</h3>
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
        <div v-for="field in resource.collection.data[0]" class="row field-row py-2 align-items-center"
             :class="[!isHeaderField(field.component) ? 'mx-0' : 'header-field']">

          <template v-if="!isHeaderField(field.component)">
            <div class="col-3 mb-1 mb-lg-0 text-muted">
              <span>{{ field.name }} <i class="fas fa-language" v-if="field.translatable"></i></span><br>
              <small>{{ field.description }}</small>
            </div>
            <div class="col-12 col-md-12 col-lg-9 col-xl-7 align-self-center">
              <component :is="`${field.component}-editable`" :field="field" :formData="formData"></component>
            </div>
          </template>

          <template v-else>
            <div class="col-12">
              <div v-html="field.value"></div>
            </div>
          </template>

        </div>
      </div>

    </div>

    <div class="px-4 py-3 text-right">
      <div>
        <button class="btn btn-primary" @click="edit">Update {{ resource.labels.singular.toLowerCase() }}</button>
      </div>
    </div>

  </div>
</template>

<script>
  export default {
    data() {
      return {
        resource: null,
        formData: new FormData()
      }
    },
    methods: {
      getResource: function () {
        this.$root.enableLoader();
        this.$http.get(this.$route.fullPath).then((response) => this.resource = response.data);
      },
      isHeaderField: function (component) {
        return component === 'header-field';
      },
      edit: function () {
        this.formData.append('collection', JSON.stringify(this.resource.collection.data[0]));
        this.$http.post(this.$route.fullPath, this.formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} edited successfully`);
            this.$router.push({
              name: 'index',
              params: {resourceName: this.$route.params.resourceName},
              query: {...this.$route.query}
            });
          }
        })
      },
      getResourceName: function () {
        return (!this.path) ? this.$route.params.resourceName : this.path
      },
      getPrimaryField: function (collection) {
        return collection.find(field => {
            if (field.primary === true) return field
          }
        );
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
      },
    },
    beforeMount: function () {
      this.getResource();
    },
    updated() {
      $('.header-field').prev().addClass("border-0");
    }
  }
</script>

<style scoped>

</style>
