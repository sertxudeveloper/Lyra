<template>
  <div class="dropdown" id="contextMenuElement" style="display:none">
    <div class="dropdown-menu show">
      <a class="dropdown-item" href="#" @click="viewDetails">
        <i class="far fa-eye"></i>
        <span class="ml-3">View Details</span>
      </a>
      <a class="dropdown-item" href="#" @click="download" v-if="element && element.mime !== 'directory'">
        <i class="fas fa-download"></i>
        <span class="ml-3">Download</span>
      </a>
      <a class="dropdown-item" href="#" @click="rename">
        <i class="fas fa-pencil-alt"></i>
        <span class="ml-3">Rename</span>
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" @click="copy">
        <i class="far fa-copy"></i>
        <span class="ml-3">Copy</span>
      </a>
      <a class="dropdown-item" href="#" @click="move">
        <i class="fas fa-arrow-right"></i>
        <span class="ml-3">Move</span>
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" @click="remove">
        <i class="fas fa-trash-alt"></i>
        <span class="ml-3">Remove</span>
      </a>
    </div>

    <details-modal :element="element" ref="detailsModal" v-if="showDetailsModal"/>

    <folder-tree-modal :folder-tree="folderTree" ref="folderTreeModal"
                       :action="getFolderTreeAction" v-if="showFolderTreeModal" @submit="folderTreeSubmit"/>

  </div>
</template>

<script>
  import DetailsModal from '../Modals/DetailsModal'
  import FolderTreeModal from '../Modals/FolderTreeModal'
  import Swal from 'sweetalert2'

  export default {
    components: {DetailsModal, FolderTreeModal},
    props: ['element', 'folderTree'],
    data() {
      return {
        showDetailsModal: false,
        showFolderTreeModal: false,
        folderTreeModalAction: null,
      }
    },
    computed: {
      getFolderTreeAction: function () {
        if (this.folderTreeModalAction === 'move') return 'Move';
        if (this.folderTreeModalAction === 'copy') return 'Copy';
      }
    },
    methods: {
      viewDetails: function () {
        this.showDetailsModal = true
      },
      download: function () {
        const link = document.createElement('a');
        link.setAttribute('href', this.element.storage_path);
        link.setAttribute('download', this.element.name);
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      },
      rename: function () {
        let extension = this.element.name.slice((this.element.name.lastIndexOf(".") - 1 >>> 0) + 2);
        if (extension) extension = '.' + extension;
        Swal.fire({
          title: `Renaming "${this.element.name}"`,
          input: 'text',
          inputValue: extension,
          inputPlaceholder: 'Enter the new filename',
          showCancelButton: true,
          confirmButtonText: 'Save'
        }).then(data => {
          if (data.value) {
            this.$http.post(`${this.$route.path}/rename`, {
              element: this.element,
              newName: data.value,
              disk: this.$route.query.disk
            }).then((response) => console.log(response));
          }
        });
      },
      copy: function () {
        this.showFolderTreeModal = false;
        this.folderTreeModalAction = null;
        this.folderTreeModalAction = "copy";
        this.showFolderTreeModal = true;
      },
      move: function () {
        this.showFolderTreeModal = false;
        this.folderTreeModalAction = null;
        this.folderTreeModalAction = "move";
        this.showFolderTreeModal = true;
      },
      folderTreeSubmit: function (selectedFolder) {
        this.$http.post(`${this.$route.path}/${this.folderTreeModalAction}`, {
          element: this.element,
          newPath: selectedFolder.path,
          disk: this.$route.query.disk
        }).then(response => this.$emit('reload-viewer'));
      },
      remove: function () {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            this.$http.post(`${this.$route.path}/delete`, {
              element: this.element,
              disk: this.$route.query.disk
            }).then(response => {
              if (response.status === 200) {
                Swal.fire(
                  'Deleted!',
                  `The file "${this.element.name}" has been deleted.`,
                  'success'
                ).then(() => {
                  this.$emit('reload-viewer');
                })
              }
            });
          }
        })
      },
    }
  }
</script>

<style scoped>

</style>
