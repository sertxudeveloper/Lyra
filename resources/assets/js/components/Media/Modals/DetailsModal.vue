<template>
  <div class="modal fade preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-muted">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row justify-content-center">
            <div class="col-3 text-muted">Name:</div>
            <div class="col-9">{{element.name}}</div>
          </div>
          <div class="row justify-content-center" v-if="element.path !== undefined">
            <div class="col-3 text-muted">Path:</div>
            <div class="col-9">
              <a :href="element.storage_path" class="path" target="_blank"
                 v-if="element.storage_path">{{element.path}}</a>
              <span v-else class="path">{{element.path}}</span>
            </div>
          </div>
          <div class="row justify-content-center" v-if="element.mime !== undefined">
            <div class="col-3 text-muted">Mime:</div>
            <div class="col-9">{{element.mime}}</div>
          </div>
          <div class="row justify-content-center" v-if="element.size !== undefined">
            <div class="col-3 text-muted">Size:</div>
            <div class="col-9">{{humanFileSize(element.size, true)}}</div>
          </div>
          <div class="row justify-content-center" v-if="element.last_modified !== undefined">
            <div class="col-3 text-muted">Last Modified:</div>
            <div class="col-9">{{formatDate(element.last_modified)}}</div>
          </div>
          <div class="row justify-content-center" v-if="element.visibility !== undefined">
            <div class="col-3 text-muted">Visibility:</div>
            <div class="col-9">{{element.visibility}}</div>
          </div>
          <div class="row justify-content-center" v-if="element.files_count !== undefined">
            <div class="col-3 text-muted">Files count:</div>
            <div class="col-9">{{element.files_count}}</div>
          </div>
          <div class="row justify-content-center" v-if="element.directories_count !== undefined">
            <div class="col-3 text-muted">Directories count:</div>
            <div class="col-9">{{element.directories_count}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        player: null
      }
    },
    props: ['element'],
    mounted() {
      $(this.$el).appendTo('body').modal('show');
      $(this.$el).on('hidden.bs.modal', e => {
        this.$parent.showDetailsModal = false;
        $(this.$el).modal('dispose');
      })
    },
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
      formatDate: function (timestamp) {
        const date = new Date(timestamp * 1000);

        let hour = this.pad(date.getHours());
        let minute = this.pad(date.getMinutes());
        let seconds = this.pad(date.getSeconds());

        let day = this.pad(date.getDate());
        let month = this.pad(date.getMonth() + 1);
        let year = date.getFullYear();

        return `${hour}:${minute}:${seconds} ${day}/${month}/${year}`
      },
      pad: function (number) {
        if (number < 10 && number >= 0) number = "0" + number;
        return number;
      }
    }
  }
</script>

<style scoped>
  .preview-modal .path {
    overflow-wrap: break-word;
  }
</style>
