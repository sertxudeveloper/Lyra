<template>
  <div class="dropdown" id="contextMenu" style="display:none">
    <div class="dropdown-menu show">
      <a class="dropdown-item" href="#" @click="newFolder"><i class="fas fa-folder-plus"></i><span class="ml-3">New Folder</span></a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" @click="uploadFiles"><i class="fas fa-file-upload"></i><span class="ml-3">Upload Files</span></a>
      <a class="dropdown-item" href="#" @click="uploadFolders"><i class="far fa-folder"></i><span class="ml-3">Upload Folders</span></a>
    </div>
  </div>
</template>

<script>
  export default {
    methods: {
      newFolder: function () {

      },
      uploadFiles: function () {

      },
      uploadFolders: function () {

      },
    }
  }
</script>

<style scoped>

</style>
