<template>
  <div class="text-right">

    <template v-if="field.multiple">
      <div class="mb-2 mx-0 row">
        <div class="col-4 p-1 text-left" v-for="(element, key) in field.value">
          <div class="position-relative">
            <img :src="field.storage_path + element" :id="`${field.column}-image`" class="img-thumbnail">
            <span class="bg-white input-group-text text-primary cursor-pointer" @click="download(element)">
              <i class="fas fa-download"></i>
            </span>
          </div>
        </div>
      </div>
    </template>

    <template v-else>
      <div class="mb-2 mx-0 row">
        <div class="col-12 pl-0 text-left">
          <div class="position-relative">
            <img :src="field.storage_path + field.value" :id="`${field.column}-image`" class="img-thumbnail">
            <span class="bg-white input-group-text text-primary cursor-pointer" @click="download(field.value)">
              <i class="fas fa-download"></i>
            </span>
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
  .img-thumbnail ~ span {
    display: none;
    position: absolute;
    right: 15px;
    top: 15px;
  }

  .img-thumbnail:hover ~ span {
    display: block;
  }

  .img-thumbnail ~ span:hover {
    display: block;
  }
</style>
