<template>
  <div class="card card-directory" :class="{'selected': selectClass}" @dblclick="$emit('change-path', element.path)"
       @click.ctrl.exact="$emit('select-element', element)" @click.exact="$emit('clear-selection')">
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
        let index = this.selectedElements.indexOf(this.element);
        return (index !== -1);
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
