<template>
  <div class="text-right">
    <input type="text" v-model="field.value" :title="field.name" class="form-control" :id="field.column"
           :maxlength="(field.size && field.size.hardMode) ? field.size.number : false"
           pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$">
    <small v-if="field.size" class="font-weight-bold text-muted">
      <span :class="classLength">{{ length }}</span> / {{ field.size.number }}
    </small>
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData'],
    computed: {
      length: function () {
        return (this.field.value) ? this.field.value.length : 0;
      },
      classLength: function () {
        let classLength = '';

        if ((this.field.size.number - this.length) > 10) {
          classLength = 'text-success';
        } else if ((this.field.size.number - this.length) <= 10 && (this.field.size.number - this.length) >= 0) {
          classLength = 'text-warning';
        } else {
          classLength = 'text-danger';
        }

        return classLength;
      }
    },
    mounted() {
      let canBeSluglify = !this.field.value;
      if (!canBeSluglify) return null;
      $(`#${this.field.target}`).on('input', () => {
        this.field.value = this.slugify($(`#${this.field.target}`)[0].value)
      })
    },
    methods: {
      slugify: function (string) {
        const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;';
        const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnooooooooprrsssssttuuuuuuuuuwxyyzzz------';
        const p = new RegExp(a.split('').join('|'), 'g');

        return string.toString().toLowerCase()
          .replace(/\s+/g, '-') // Replace spaces with -
          .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
          .replace(/&/g, '-and-') // Replace & with 'and'
          .replace(/[^\w\-]+/g, '') // Remove all non-word characters
          .replace(/\-\-+/g, '-') // Replace multiple - with single -
          .replace(/^-+/, '') // Trim - from start of text
          .replace(/-+$/, '') // Trim - from end of text
      }
    }
  }
</script>

<style scoped>

</style>
