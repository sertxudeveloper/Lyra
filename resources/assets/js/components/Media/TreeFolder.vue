<template>
  <div class="h-100 tree-folder">
    <lyra-loader v-if="isLoaderEnabled" class="py-5"></lyra-loader>
    <ul v-else class="pt-2">
      <tree-folder-contents class="item" :folder="folders" @change-path="changePathEvent"></tree-folder-contents>
    </ul>
  </div>
</template>

<script>
  import TreeFolderContents from './TreeFolderContents'

  export default {
    components: {TreeFolderContents},
    data() {
      return {
        folders: {
          name: "<i class=\"fas fa-home\"></i> My disk",
          path: null,
          children: null
        }
      }
    },
    methods: {
      getFolderTree: function () {
        const path = this.$route.fullPath.split('?')[0];
        const query = this.$route.fullPath.split('?')[1];
        this.$http.get(`${path}/tree?${query}`).then((response) => this.folders.children = response.data);
      },
      changePathEvent: function (path) {
        this.$emit('change-path', path)
      }
    },
    beforeMount() {
      this.getFolderTree()
    },
    computed: {
      isLoaderEnabled: function () {
        return (this.$root.loader === false && this.folders.children === null)
      },
    }
  }
</script>

<style scoped>
.tree-folder li.item span {
  white-space: nowrap;
}
</style>
