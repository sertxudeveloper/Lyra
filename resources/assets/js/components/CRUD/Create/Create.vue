<template>
  <div v-if="$root.loader === false && resource !== null" class="pb-5 pt-4 px-lg-5">

    <div class="align-items-baseline d-flex justify-content-between">
      <div>
        <h3 class="pb-3">New {{ resource.labels.singular.toLowerCase() }}</h3>
      </div>
      <div class="mb-2 text-right">
        <div class="btn-group" role="group" aria-label="Languages available">
          <button type="button" class="btn" :class="languagesClass(language, key)"
                  data-toggle="tooltip" data-placement="top"
                  :title="key !== 0 ? 'Additional languages disabled until the model is created': ''"
                  v-for="(language, key) in resource.languages">{{language.toUpperCase()}}
          </button>
        </div>
      </div>
    </div>

    <form class="align-items-baseline d-flex justify-content-between" id="createForm">

      <div class="panel box-dark-shadow col-12 py-2 w-100">
        <div v-for="field in resource.collection.data[0]" class="row field-row py-2 align-items-center"
             :class="[!isHeadingField(field.component) ? 'mx-0' : 'heading-field']">

          <template v-if="!isHeadingField(field.component)">
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

    </form>

    <div class="py-3 text-right">
      <div>
        <button class="btn btn-primary" @click="create">Create {{ resource.labels.singular.toLowerCase() }}</button>
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
        this.$http.get(this.$route.fullPath).then(response => this.resource = response.data);
      },
      isHeadingField: function (component) {
        return component === 'heading-field';
      },
      create: function () {
        const isValid = $('#createForm')[0].reportValidity();
        if (!isValid) return false;

        this.formData.append('collection', JSON.stringify(this.resource.collection.data[0]));
        this.$http.post(this.$route.fullPath, this.formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          if (response.status === 200) {
            toastr.success(`${this.resource.labels.singular} created successfully`);
            return this.$router.back()
          }
        })
      },
      languagesClass: function (lang, key) {
        return {
          'btn-secondary': (!this.$route.query.lang && key === 0) || (this.$route.query.lang && this.$route.query.lang === lang),
          'btn-outline-dark': (!this.$route.query.lang && key !== 0) || (this.$route.query.lang && this.$route.query.lang !== lang),
          'disabled': key !== 0
        }
      },
    },
    beforeMount: function () {
      this.getResource();
    },
    updated: function () {
      $('[data-toggle="tooltip"]').tooltip();
      $('.heading-field').prev().addClass("border-0");
    }
  }
</script>

<style scoped>

</style>
