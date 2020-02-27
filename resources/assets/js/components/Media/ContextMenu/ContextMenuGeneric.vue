<template>
  <div class="dropdown" id="contextMenu" style="display:none">
    <div class="dropdown-menu show">
      <a class="dropdown-item" href="#" @click="newFolder"><i class="fas fa-folder-plus"></i><span class="ml-3">New Folder</span></a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" @click="uploadFiles"><i class="fas fa-file-upload"></i><span class="ml-3">Upload Files</span></a>
      <a class="dropdown-item" href="#" @click="uploadFolders"><i class="far fa-folder"></i><span class="ml-3">Upload Folders</span></a>
    </div>
    <input type="file" ref="uploadFiles" class="d-none" @change="handleFileUpload" multiple/>
    <input type="file" ref="uploadFolders" webkitdirectory directory class="d-none" @change="handleFolderUpload" multiple/>
  </div>
</template>

<script>
  import Swal from 'sweetalert2'

  export default {
    data: function () {
      return {
        // files: []
      }
    },
    methods: {
      newFolder: function () {
        Swal.fire({
          title: `Create a folder`,
          input: 'text',
          inputPlaceholder: 'Enter the name of the new folder',
          showCancelButton: true,
          confirmButtonText: 'Create',
          reverseButtons: true
        }).then(data => {
          if (data.value) {
            this.$http.post(`${this.$route.path}/newFolder`, {
              path: this.$route.query.path ? this.$route.query.path : null,
              disk: this.$route.query.disk ? this.$route.query.disk : false,
              name: data.value
            }).then((response) => this.$emit('reload-viewer'));
          }
        });
      },
      uploadFiles: function () {
        $(this.$refs.uploadFiles).trigger('click');
      },
      uploadFolders: function () {
        $(this.$refs.uploadFolders).trigger('click');
      },
      handleFileUpload: function () {
        let formData = new FormData();

        Array.from(this.$refs.uploadFiles.files).forEach((file, key) => {
          formData.append(`file-${key}`, file);
        });

        formData.append('path', this.$route.query.path ? this.$route.query.path : '/');
        formData.append('disk', this.$route.query.disk ? this.$route.query.disk : false);

        this.$http.post(`${this.$route.path}/upload`, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          if (response.status === 200) {
            toastr.success(`Files uploaded successfully`);
            this.$emit('reload-viewer');
          }
        })
      },
      handleFolderUpload: function () {
        let formData = new FormData();

        Array.from(this.$refs.uploadFolders.files).forEach((file, key) => {
          let relativePath = file.webkitRelativePath.split('/');
          relativePath.pop();
          formData.append(`folder-${key}`, relativePath.join('/'));
          formData.append(`file-${key}`, file);
        });

        formData.append('path', this.$route.query.path ? this.$route.query.path : null);
        formData.append('disk', this.$route.query.disk ? this.$route.query.disk : false);

        this.$http.post(`${this.$route.path}/uploadFolder`, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          if (response.status === 200) {
            toastr.success(`Files uploaded successfully`);
            this.$emit('reload-viewer');
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
