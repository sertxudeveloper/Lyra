<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb m-0 bg-transparent">

      <li class="breadcrumb-item"
          @drop.ctrl.exact="$emit('drop-element-copy', '/')" @drop.exact="$emit('drop-element-move', '/')"
          @dragover.prevent draggable="false">
        <a @click="breadcrumbChangePath(null)" href="#">
          <i class="fas fa-home"></i> My disk
        </a>
      </li>

      <template v-for="index in reverseKeys" v-if="currentPath.length">
        <li class="breadcrumb-item" v-if="index !== 0"
            @drop.ctrl.exact="$emit('drop-element-copy', getBreadcrumbPath(index))"
            @drop.exact="$emit('drop-element-move', getBreadcrumbPath(index))"
            @dragover.prevent draggable="false">
          <a @click="breadcrumbChangePath(getBreadcrumbPath(index))" href="#">{{currentPath.split('/').reverse()[index]}}</a>
        </li>

        <li class="breadcrumb-item active" aria-current="page" v-else
            @drop.ctrl.exact="$emit('drop-element-copy', getBreadcrumbPath(index))"
            @drop.exact="$emit('drop-element-move', getBreadcrumbPath(index))"
            @dragover.prevent draggable="false">
          <span>{{currentPath.split('/').reverse()[index]}}</span>
        </li>

      </template>

    </ol>
  </nav>
</template>

<script>
  export default {
    data() {
      return {
        currentPath: this.$route.query.path ? this.$route.query.path : '',
      }
    },
    methods: {
      breadcrumbChangePath: function (path) {
        this.currentPath = path;
        this.$emit('change-path', path)
      },
      getBreadcrumbPath: function (index) {
        const inverseIndex = Math.abs(this.currentPath.split('/').length - index);
        return this.currentPath.split('/').slice(0, inverseIndex).reduce((a, c) => a + '/' + c)
      }
    },
    computed: {
      reverseKeys: function () {
        return [...Array(this.currentPath.split('/').slice(1).length).keys()].reverse()
      }
    },
  }
</script>

<style scoped>

</style>
