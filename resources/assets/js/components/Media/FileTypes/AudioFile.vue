<template>
  <div class="card card-audio-file" :class="{'selected': selectClass}" @dblclick="openPreview"
       @click.ctrl.exact="$emit('select-element')" @click.exact="$emit('clear-selection')"
       draggable="true" @dragover.prevent @dragstart="$emit('drag-start')" @dragend="$emit('drag-end')">
    <div class="row no-gutters">
      <div class="card-preview d-flex align-items-center justify-content-center">
        <i class="fas fa-music"></i>
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
    methods: {
      openPreview: function () {
        this.$parent.previewElement = this.element;
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
  .card-audio-file .row {
    height: 70px;
    width: 200px;
    pointer-events: none;
  }

  .card-audio-file i {
    font-size: 40px;
  }

  .card-audio-file .body {
    width: 130px;
  }
</style>
