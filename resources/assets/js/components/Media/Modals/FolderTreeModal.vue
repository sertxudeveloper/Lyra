<template>

  <div class="modal fade bd-example-modal-xl folder-tree-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-sm">
      <div class="modal-content">


        <!--<div class="modal-header">
          <div class="row">
            <h5 class="modal-title">Move to</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="row">
            <div class="d-flex justify-content-between align-items-center" v-if="showParentFolderLink && parentName">
              <span @click="openParentFolder"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{parentName}}</span>
            </div>
          </div>
        </div>-->


        <div class="modal-header pb-2">
          <div class="m-auto row w-100">
            <div class="align-items-start col-12 d-flex mb-2 p-0">
              <h5 class="modal-title">{{action}} to</h5>
              <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="col-12 p-0">
              <div class="d-flex justify-content-between align-items-center" v-if="showParentFolderLink && parentName">
                <span @click="openParentFolder">
                  <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;{{parentName}}
                </span>
              </div>
            </div>
          </div>
        </div>


        <div class="modal-body p-0" v-if="folderTree && displayTree">

          <ul class="list-group">
            <li
              class="align-items-center border-0 d-flex justify-content-between list-group-item list-group-item-action m-0 p-0"
              v-for="folder in displayTree"
              :class="isSelected(folder)" :title="folder.name">
              <span class="h-100 px-3 py-2 w-100 cursor-pointer" @click="setFolder(folder)">{{folder.name}}</span>
              <button class="btn btn-sm btn-outline-dark show-on-hover mr-2" @click="openFolder(folder)">
                <i class="fas fa-arrow-right"></i>
              </button>
            </li>

            <li class="align-items-center border-0 d-flex justify-content-between list-group-item list-group-item-action m-0 p-0 disabled"
                :title="fileTooltipTitle(file)" v-for="file in displayFiles">
              <span class="h-100 px-3 py-2 w-100">{{file.name}}</span>
            </li>
          </ul>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :disabled="!selectedFolder" @click="submitAction">{{action}}</button>
        </div>

      </div>
    </div>
  </div>

</template>

<script>
  export default {
    data() {
      return {
        displayTree: null,
        displayFiles: null,
        parentName: null,
        selectedFolder: null,
      }
    },
    props: ['action', 'folderTree'],
    mounted() {
      this.displayTree = this.folderTree.children;
      this.displayFiles = this.folderTree.files;
      $(this.$el).appendTo('body').modal('show');
      $(this.$el).on('hidden.bs.modal', e => {
        this.$parent.showFolderTreeModal = false;
        $(this.$el).modal('dispose');
      });
    },
    methods: {
      setFolder: function (folder) {
        this.selectedFolder = folder;
      },
      openFolder: function (folder) {
        this.parentName = folder.name;
        this.displayTree = folder.children;
        this.displayFiles = folder.files;
      },
      openParentFolder: function () {
        let parent = this.getParentTree(this.folderTree, this.displayTree);
        this.displayTree = parent.children;
        this.displayFiles = parent.files;
        this.parentName = parent.name ? parent.name : null;
      },
      getParentTree: function (haystack, needle) {
        if (!haystack.children) return null;
        let response = null;
        for (let key in haystack.children) {
          let folder = haystack.children[key];
          if (folder.children === needle) return haystack;
          let parent = this.getParentTree(folder, needle);
          if (parent) response = parent;
        }
        return response;
      },
      fileTooltipTitle: function (file) {
        return `"${file.name}" is not a folder`
      },
      isSelected: function (folder) {
        return {
          'active': this.selectedFolder && this.selectedFolder.path === folder.path
        };
      },
      submitAction: function () {
        $(this.$el).modal('hide');
        this.$emit('submit', this.selectedFolder)
      }
    },
    computed: {
      showParentFolderLink: function () {
        return JSON.stringify(this.folderTree) !== JSON.stringify(this.displayTree)
      },
    }
  }
</script>

<style scoped>
  .folder-tree-modal {
    display: flex!important;
    justify-content: center;
    flex-direction: column;
  }

  .folder-tree-modal .modal-dialog {
    max-height: 500px;
  }

  .folder-tree-modal .modal-dialog .modal-content {
    width: 300px;
  }

  .folder-tree-modal li.list-group-item {
    border-radius: 0 !important;
  }

  .folder-tree-modal li.list-group-item span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .folder-tree-modal li.list-group-item.disabled {
    pointer-events: all;
  }

  .folder-tree-modal li.list-group-item.disabled span {
    pointer-events: none;
  }

  .folder-tree-modal li.list-group-item.list-group-item-action:hover .show-on-hover {
    opacity: 1;
  }

  .folder-tree-modal li.list-group-item.list-group-item-action .show-on-hover {
    opacity: 0;
  }
</style>
