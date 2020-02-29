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
        this.$http.post(`${this.$route.path}/download`, {
          element: this.element,
          disk: this.$route.query.disk
        }, {responseType: 'blob'}).then((response) => {
          const fileURL = URL.createObjectURL(response.data);
          let link = document.createElement('a');
          link.setAttribute('href', fileURL);
          link.setAttribute('download', this.element.name);
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          URL.revokeObjectURL(fileURL);
        });
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
          confirmButtonText: 'Save',
          onOpen: function () {
            let input = Swal.getInput();
            input.setSelectionRange(0, 0);
          }
        }).then(data => {
          if (data.value) {
            this.$http.post(`${this.$route.path}/rename`, {
              element: this.element,
              newName: data.value,
              disk: this.$route.query.disk
            }).then((response) => {
              if (response.status === 200) {
                let type = (this.element.mime === 'directory') ? 'folder' : 'file';
                toastr.success(`The ${type} "${this.element.name}" has been renamed to "${data.value}".`);
                this.$emit('reload-viewer');
              }
            });
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
          confirmButtonText: 'Yes, delete it!',
          confirmButtonColor: '#d61c04',
          focusCancel: true,
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            this.$http.post(`${this.$route.path}/delete`, {
              element: this.element,
              disk: this.$route.query.disk
            }).then(response => {
              if (response.status === 200) {
                let type = (this.element.mime === 'directory') ? 'folder' : 'file';
                toastr.success(`The ${type} "${this.element.name}" has been deleted.`);
                this.$emit('reload-viewer');
              }
            });
          }
        })
      },
    }
  }
</script>

<style scoped>
  #contextMenuElement .dropdown-menu {
    width: 240px;
  }
</style>
