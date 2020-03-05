<template>
  <div class="card card-directory" :class="{'selected': selectClass}" @dblclick="$emit('change-path')"
       @click.ctrl.exact="$emit('select-element')" @click.exact="$emit('clear-selection')"
       @drop.ctrl.exact="$emit('drop-element-copy')" @drop.exact="$emit('drop-element-move')"
       @dragstart="$emit('drag-start')" @dragend="$emit('drag-end')"
       @dragover.prevent draggable="true">
    <div class="row no-gutters">
      <div class="card-preview d-flex align-items-center justify-content-center">
        <i class="fas fa-folder"></i>
      </div>
      <div class="body pr-2">
        <div class="card-body h-100 px-0 py-2">
          <p class="card-text element-name my-1">{{element.name}}</p>
          <p class="card-text d-flex flex-column">
            <small class="text-muted mb-1">Items: {{element.files_count + element.directories_count}}</small>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['element', 'selectedElements'],
    computed: {
      selectClass: function () {
        let fount = this.selectedElements.find(element => element.path === this.element.path);
        return (fount);
      }
    }
  }
</script>

<style scoped>
  .card-directory .row {
    height: 70px;
    width: 200px;
    pointer-events: none;
  }

  .card-directory i {
    font-size: 40px;
  }

  .card-directory .body {
    width: 130px;
  }
</style>
