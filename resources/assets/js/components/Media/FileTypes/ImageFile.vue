<template>
  <div class="card card-image-file" :class="{'selected': selectClass}" @dblclick="openPreview"
       @click.ctrl.exact="$emit('select-element')" @click.exact="$emit('clear-selection')"
       @dragstart="$emit('drag-start')" @dragend="$emit('drag-end')"
       draggable="true" @dragover.prevent>
    <div class="row no-gutters">
      <div class="card-preview d-flex align-items-center justify-content-center">
        <div class="justify-content-around m-2 rounded" v-if="element.storage_path && !showIcon">
          <img :src="element.storage_path" :alt="element.name" @error="onImgError()">
        </div>
        <i class="fas fa-image" v-else></i>
      </div>
      <div class="body pr-2">
        <div class="card-body h-100 px-0 py-2">
          <p class="card-text element-name my-1">{{element.name}}</p>
          <p class="card-text d-flex flex-column">
            <small class="text-muted mb-1">{{humanFileSize(element.size, true)}}</small>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {humanFileSize} from '../../../methods/humanFileSize'

  export default {
    props: ['element', 'selectedElements'],
    data: function () {
      return {
        showIcon: false,
      }
    },
    methods: {
      openPreview: function () {
        this.$parent.previewElement = this.element;
      },
      onImgError: function () {
        this.showIcon = true;
      },
      humanFileSize: function (size, si) {
        return humanFileSize(size, si)
      }
    },
    computed: {
      selectClass: function () {
        let fount = this.selectedElements.find(element => element.path === this.element.path);
        return (fount);
      }
    }
  }
</script>

<style scoped>
  .card-image-file div.row {
    height: 70px;
    width: 200px;
    pointer-events: none;
  }

  .card-image-file img {
    height: 100%;
    width: auto;
  }

  .card-image-file .card-preview > div {
    overflow: hidden;
    height: calc(100% - 1rem);
    width: inherit;
    display: flex;
  }

  .card-image-file .row .body {
    width: 130px;
  }

  .card-image-file i {
    font-size: 40px;
  }
</style>
