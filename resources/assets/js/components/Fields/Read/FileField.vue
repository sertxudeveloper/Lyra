<template>
  <div class="text-right">

    <template v-if="field.multiple">
      <div class="mb-2 mx-0 row" v-for="element in field.value">
        <div class="col-9 text-left px-0">
          <div class="cursor-pointer input-group-text position-relative" @click="download(element)">
            <span class="mr-3 text-primary">
              <i class="fas fa-download"></i>
            </span>
            <span>{{ getName(element) }}</span>
          </div>
        </div>
      </div>
    </template>

    <template v-else>
      <div class="mb-2 mx-0 row">
        <div class="col-9 text-left px-0" v-if="field.value">
          <div class="cursor-pointer input-group-text position-relative" @click="download(field.value)">
            <span class="mr-3 text-primary">
              <i class="fas fa-download"></i>
            </span>
            <span>{{ getName(field.value) }}</span>
          </div>
        </div>
      </div>
    </template>

  </div>
</template>

<script>
  export default {
    props: ['field'],
    methods: {
      download: function (filename) {
        const element = document.createElement('a');
        element.setAttribute('href', this.field.storage_path + filename);
        element.setAttribute('download', filename.replace(/^.*[\\\/]/, ''));
        element.setAttribute('target', '_blank');
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
      },
      getName: function (filename) {
        if (!filename) return "";
        return filename.replace(/^.*[\\\/]/, '');
      }
    }
  }
</script>

<style scoped>
  .text-primary ~ span {
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
  }
</style>
