<template>
  <div class="card" @dblclick="openPreview">
    <div class="row no-gutters" style="height: 70px;width: 200px;pointer-events: none;">
      <div class="card-preview d-flex align-items-center justify-content-center">
        <div class="justify-content-around m-2 rounded"
             style="overflow: hidden;height: calc(100% - 1rem);width: inherit;display: flex;">
          <img :src="element.storage_path" :alt="element.name" style="height: 100%;width: auto;">
        </div>
      </div>
      <div class="pr-2" style="width:130px;">
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
  export default {
    props: ['element'],
    methods: {
      humanFileSize: function (bytes, si) {
        const thresh = si ? 1000 : 1024;
        if (Math.abs(bytes) < thresh) {
          return bytes + ' B';
        }
        const units = si
          ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
          : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        let u = -1;
        do {
          bytes /= thresh;
          ++u;
        } while (Math.abs(bytes) >= thresh && u < units.length - 1);
        return bytes.toFixed(1) + ' ' + units[u];
      },
      openPreview: function () {
        this.$parent.previewElement = this.element;
      },
    },
  }
</script>

<style scoped>

</style>
