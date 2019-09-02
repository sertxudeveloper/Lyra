<template>
  <div class="modal fade bd-example-modal-xl preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{element.name}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-0">

          <template v-if="/^image\/\w+$/.test(element.mime)">
            <img :src="element.storage_path" :alt="element.name"
                 style="height: 100%;width: 100%;display: block;margin: 0 auto;">
          </template>

          <template v-else-if="/^video\/\w+$/.test(element.mime)">
            <video :src="element.storage_path" :alt="element.name" controls preload="auto"
                   ref="previewVideo" style="height: 100%;width: 100%;display: block;margin: 0 auto;"></video>
          </template>

          <template v-else-if="/^audio\/\w+$/.test(element.mime)">
            <audio :src="element.storage_path" :alt="element.name" controls preload="auto"
                   ref="previewAudio" style="height: 100%;width: 100%;display: block;margin: 0 auto;"></audio>
          </template>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Plyr from 'plyr';

  export default {
    data() {
      return {
        player: null
      }
    },
    props: ['element'],
    mounted() {
      if (/^video\/\w+$/.test(this.element.mime)) {
        this.player = new Plyr(this.$refs.previewVideo);
      } else if (/^audio\/\w+$/.test(this.element.mime)) {
        this.player = new Plyr(this.$refs.previewAudio);
      }

      $(this.$el).appendTo('body').modal('show');
      $(this.$el).on('hidden.bs.modal', e => {
        $(this.$el).modal('dispose');
        if (this.player) this.player.destroy();
        this.$parent.previewElement = null;
      })
    }
  }
</script>

<style scoped>
  @import "~plyr/dist/plyr.css";
</style>
