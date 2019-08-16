<template>
  <div class="text-right">
    <template v-if="field.multiple">
      <div class="input-group mb-2" v-for="(element, key) in field.value">
        <div class="input-group-prepend">
          <span class="bg-white input-group-text text-primary cursor-pointer" @click="download(element)">
            <i class="fas fa-download"></i>
          </span>
        </div>
        <input type="text" class="bg-white border-left-0 border-right-0 form-control" disabled :value="getName(element)">
        <div class="input-group-append">
          <span class="bg-white input-group-text text-danger cursor-pointer" @click="removeFile(key)">
            <i class="far fa-trash-alt"></i>
          </span>
        </div>
      </div>
      <button class="btn btn-outline-primary" @click="openFileDialog(field.column)">Add New File</button>
      <input type="file" :id="field.column" class="d-none" @change="addNewFile($event)" multiple>
    </template>
    <template v-else>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <span class="bg-white input-group-text text-primary cursor-pointer" @click="download(field.value)">
            <i class="fas fa-download"></i>
          </span>
        </div>
        <input type="text" class="bg-white border-left-0 border-right-0 form-control" disabled :value="getName(field.value)" :id="`${field.column}-text`">
        <div class="input-group-append">
          <span class="bg-white input-group-text text-danger cursor-pointer" @click="clearFile(field.column)">
            <i class="far fa-trash-alt"></i>
          </span>
        </div>
      </div>
      <button class="btn btn-outline-primary" @click="openFileDialog(field.column)">Replace File</button>
      <input type="file" :id="field.column" class="d-none" @change="updateFile($event)">
    </template>
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData'],
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
      },
      openFileDialog: function (id) {
        $(`#${id}`).trigger('click')
      },
      updateFile: function (e) {
        let files = e.target.files || e.dataTransfer.files;
        if (files && files[0]) {
          this.field.value = files[0].name;
          this.formData.append(`${e.target.id}-0`, files[0]);
        }
      },
      clearFile: function (id){
        $(`#${id}`)[0].value = null;
        $(`#${id}-text`)[0].value = null;
      },
      addNewFile: function (e) {
        if (!this.field.value) this.field.value = [];
        let files = event.target.files;

        for (let i = 0; i < files.length; i++) {
          let file = files[i];
          let index = -1;
          this.field.value.forEach((filename, key) => {
            if (this.getName(filename) === file.name) index = key
          });
          if (index === -1) {
            this.field.value.push(file.name);
            this.field.value.forEach((filename, key) => {
              if (this.getName(filename) === file.name) index = key
            });
            this.formData.append(`${e.target.id}-${index}`, file);
          }
        }
      },
      removeFile: function (index) {
        this.field.value.splice(index, 1);
        this.formData.delete(`${this.field.column}-${index}`)
      }
    }
  }
</script>

<style scoped>

</style>
