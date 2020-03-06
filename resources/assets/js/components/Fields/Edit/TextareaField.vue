<template>
  <div class="text-right">
    <textarea class="form-control" :title="field.name" :id="field.column" v-model="field.value" cols="30" rows="10"
              :maxlength="(field.size && field.size.hardMode) ? field.size.number : false"></textarea>
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
    }
  }
</script>

<style scoped>
  textarea {
    min-height: 100px;
  }
</style>
