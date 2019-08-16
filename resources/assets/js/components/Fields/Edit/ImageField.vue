<template>
  <div class="text-right">
    <template v-if="field.multiple">
      <div class="mb-2 mx-0 row">
        <div class="col-4 p-1 text-left" v-for="(element, key) in field.value">
          <div class="position-relative">
            <img :src="previews[key]" :id="`${field.column}-image`" class="img-thumbnail" v-if="previews[key]">
            <img :src="field.storage_path + element" :id="`${field.column}-image`" class="img-thumbnail" v-else>
            <span class="bg-white input-group-text text-danger cursor-pointer" @click="removeFile(key)"><i class="far fa-trash-alt"></i></span>
          </div>
        </div>
      </div>

      <button class="btn btn-outline-primary" @click="openFileDialog(field.column)">Add New File</button>
      <input type="file" :id="field.column" class="d-none" @change="addNewFile($event)" multiple accept="image/*">

    </template>
    <template v-else>
      <div class="mb-2 mx-0 row">
        <div class="col-12 text-left">
          <div class="position-relative">
            <img :src="field.storage_path + field.value" :id="`${field.column}-image`" class="img-thumbnail">
            <span class="bg-white input-group-text text-danger cursor-pointer" @click="clearFile(field.column)"><i class="far fa-trash-alt"></i></span>
          </div>
        </div>
      </div>
      <button class="btn btn-outline-primary" @click="openFileDialog(field.column)">Replace File</button>
      <input type="file" :id="field.column" class="d-none" @change="updateFile($event)" accept="image/*">
    </template>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        previews: [],
        iteration: 0,
      }
    },
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
          let id = e.target.id;
          this.formData.append(`${e.target.id}-0`, files[0]);
          this.field.value = files[0].name;

          const reader = new FileReader();
          reader.onload = (e) => {
            $(`#${id}-image`)[0].src = e.target.result
          };
          reader.readAsDataURL(files[0]);
        }
      },
      clearFile: function (id) {
        $(`#${id}`)[0].value = null;
        $(`#${id}-image`)[0].src = null;
      },
      addNewFile: function (e) {
        if (!this.field.value) this.field.value = [];
        let files = event.target.files;

        for (let i = 0; i < files.length; i++) {
          let index = -1;
          this.field.value.forEach((filename, key) => {
            if (this.getName(filename) === files[i].name) index = key
          });
          if (index === -1) {
            const reader = new FileReader();
            reader.onload = (e) => {
              this.field.value.push(files[i].name);
              let index = this.field.value.indexOf(files[i].name);
              this.previews[index] = e.target.result;

              this.field.value.forEach((filename, key) => {
                if (this.getName(filename) === files[i].name) index = key
              });
              this.formData.append(`${this.field.column}-${this.iteration}`, files[i]);
              this.iteration++;
            };
            reader.readAsDataURL(files[i]);
          }
        }
      },
      removeFile: function (index) {
        this.field.value.splice(index, 1);
        this.previews.splice(index, 1);
        this.formData.delete(`${this.field.column}-${index}`)
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
