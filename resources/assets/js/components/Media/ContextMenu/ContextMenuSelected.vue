<template>
  <div class="dropdown" id="contextMenuSelected" style="display:none">
    <div class="dropdown-menu show">
      <a class="dropdown-item" href="#" @click="download">
        <i class="fas fa-download"></i>
        <span class="ml-3">Download</span>
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

    <folder-tree-modal :folder-tree="folderTree" ref="folderTreeModal"
                       :action="getFolderTreeAction" v-if="showFolderTreeModal" @submit="folderTreeSubmit"/>

  </div>
</template>

<script>
  import FolderTreeModal from '../Modals/FolderTreeModal'
  import Swal from 'sweetalert2'

  export default {
    components: {FolderTreeModal},
    props: ['elements', 'folderTree'],
    data() {
      return {
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
      download: function () {
        this.elements.forEach(element => {
          this.$http.post(`${this.$route.path}/download`, {
            element: element,
            disk: this.$route.query.disk
          }, {responseType: 'blob'}).then((response) => {
            const fileURL = URL.createObjectURL(response.data);
            let link = document.createElement('a');
            link.setAttribute('href', fileURL);
            link.setAttribute('download', element.name);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(fileURL);
          });
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
        this.elements.forEach(element => {
          this.$http.post(`${this.$route.path}/${this.folderTreeModalAction}`, {
            element: element,
            newPath: selectedFolder.path,
            disk: this.$route.query.disk
          }).then(response => this.$emit('reload-viewer'));
        });
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
            this.elements.forEach(element => {
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
