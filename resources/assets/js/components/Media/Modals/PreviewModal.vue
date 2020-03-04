<template>
  <div class="modal fade preview-modal modal-preview" tabindex="-1" role="dialog"
       aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{element.name}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-muted">&times;</span>
          </button>
        </div>
        <div class="modal-body p-0" v-if="!linkBroken">

          <template v-if="/^image\/\w+$/.test(element.mime)">
            <img :src="element.storage_path" :alt="element.name" @error="onLinkBroken">
          </template>

          <template v-else-if="/^video\/\w+$/.test(element.mime)">
            <video :src="element.storage_path" :alt="element.name" controls preload="auto"
                   @error="onLinkBroken" ref="previewVideo"></video>
          </template>

          <template v-else-if="/^audio\/\w+$/.test(element.mime)">
            <audio :src="element.storage_path" :alt="element.name" controls preload="auto"
                   @error="onLinkBroken" ref="previewAudio"></audio>
          </template>

        </div>
        <div class="modal-body p-0" v-else>
          <div class="my-5 text-center">
            <h1 class="display-2">
              <i class="fas fa-unlink"></i>
            </h1>
            <h4 class="mt-4">The file preview is not available</h4>
          </div>
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
        player: null,
        linkBroken: false,
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
    },
    methods: {
      onLinkBroken: function () {
        this.linkBroken = true;
      }
    }
  }
</script>

<style scoped>
  @import "~plyr/dist/plyr.css";

  .modal-preview video,
  .modal-preview audio {
    height: 100%;
    width: 100%;
    display: block;
    margin: 0 auto;
  }

  .modal-preview img {
    max-width: 100%;
    display: block;
    margin: 0 auto;
  }

</style>
