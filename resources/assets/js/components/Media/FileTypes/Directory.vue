<template>
  <div class="card" @dblclick="$parent.$emit('change-path', element.path)">
    <div class="row no-gutters" style="height: 70px;width: 200px;pointer-events: none;">
      <div class="card-preview d-flex align-items-center justify-content-center">
        <i class="fas fa-folder" style="font-size: 40px;"></i>
      </div>
      <div class="pr-2" style="width:130px;">
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
    props: ['element'],
  }
</script>

<style scoped>

</style>
