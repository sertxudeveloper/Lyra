<template>
  <div class="mt-3">
    <lyra-loader v-if="isLoaderEnabled" class="py-5"></lyra-loader>
    <template v-else>
      <div class="row m-0">
        <div v-for="element in elements" class="mb-3 mr-3" @dblclick="dblclickEvent(element)">
          <div class="card card-folder">
            <!--<div class="card-body">
              {{element.name}}
            </div>-->
            <div class="row no-gutters" style="height: 70px;width: 200px;">
              <div class="card-preview d-flex align-items-center justify-content-center">
                <template v-if="hasFilePreview(element.mime)">
                  <div class="justify-content-around m-2 rounded" style="overflow: hidden;height: calc(100% - 1rem);width: inherit;display: flex;">
                    <img :src="element.storage_path" :alt="element.name" style="height: 100%;width: auto;">
                  </div>
                </template>
                <template v-else>
                  <i class="fas fa-folder" style="font-size: 40px;" v-if="element.mime === 'directory'"></i>
                  <i class="fas fa-file" style="font-size: 40px;" v-else></i>
                </template>
              </div>
              <div class="pr-2" style="width:130px;">
                <div class="card-body h-100 px-0 py-2">
                  <p class="card-text element-name my-1" :title="element.name">{{element.name}}</p>
                  <p class="card-text d-flex flex-column">
                    <small class="text-muted mb-1" v-if="element.size !== undefined">{{humanFileSize(element.size, true)}}</small>
                    <!--<small class="text-muted mb-1" v-if="element.last_modified">{{formatDate(element.last_modified)}}</small>-->
                    <small class="text-muted mb-1" v-if="element.items !== undefined">Items: {{element.items}}</small>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        elements: null
      }
    },
    name: "MediaViewer",
    methods: {
      getElementsFolder: function () {
        const path = this.$route.fullPath.split('?')[0];
        const query = this.$route.fullPath.split('?')[1];
        this.$http.get(`${path}/files?${query}`).then((response) => this.elements = response.data);
      },
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
      formatDate: function(timestamp) {
        const date = new Date(timestamp * 1000);

        const hour = date.getHours();
        const minute = date.getMinutes();
        const seconds = date.getSeconds();

        const day = date.getDate();
        const month = date.getMonth();
        const year = date.getFullYear();

        return `${hour}:${minute}: ${seconds} ${day}/${month}/${year}`
      },
      hasFilePreview: function (mime) {
        return (/^image\/\w+$/.test(mime))
      },
      dblclickEvent: function (element) {
        if (element.mime === 'directory') return this.$emit('change-path', element.path)
      }
    },
    beforeMount() {
      this.getElementsFolder()
    },
    computed: {
      isLoaderEnabled: function () {
        return (this.$root.loader === false && this.elements === null)
      },
    },
    // watch: {
    //   '$route': function (from, to) {
    //     this.getElementsFolder()
    //   }
    // }
  }
</script>

<style scoped>
  .card-folder .card-body {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  .element-name {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .card-preview {
    height: 70px;
    width: 70px;
  }
</style>
