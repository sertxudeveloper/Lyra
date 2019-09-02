<template>
  <li>

    <div :class="{bold: isFolder}" @click="toggle" @dblclick="changePath">
      <span v-html="folder.name"></span>
      <span v-if="isFolder">[{{ isOpen ? '-' : '+' }}]</span>
    </div>

    <ul v-show="isOpen" v-if="isFolder">
      <tree-folder-contents
        class="item"
        v-for="(child, index) in folder.children"
        :key="index"
        :folder="child"
        @change-path="changePathEvent">
      </tree-folder-contents>
    </ul>

  </li>
</template>

<script>
  import TreeFolderContents from './TreeFolderContents'

  export default {
    name: "tree-folder-contents",
    components: {TreeFolderContents},
    props: ["folder"],
    data: function () {
      return {
        isOpen: this.folder.path === null
      }
    },
    computed: {
      isFolder: function () {
        return this.folder.children && this.folder.children.length
      }
    },
    methods: {
      toggle: function () {
        if (this.isFolder) this.isOpen = !this.isOpen
      },
      changePath: function () {
        this.$emit('change-path', this.folder.path)
      },
      changePathEvent: function (path) {
        this.$emit('change-path', path)
      }
    }
  }
</script>

<style scoped>

</style>
