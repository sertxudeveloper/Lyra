<template>
  <div class="markdown-body">
    <textarea v-model="field.value" ref="markdown" cols="200"></textarea>
  </div>
</template>

<script>
  import SimpleMDE from 'simplemde'
  import "../../../vendors/prism"

  export default {
    data() {
      return {
        simplemde: null
      }
    },
    props: ['field', 'formData'],
    mounted() {
      this.simplemde = new SimpleMDE({
        element: $(this.$refs.markdown)[0],
        forceSync: true,
        autofocus: true,
        spellChecker: false,
        hideIcons: ["side-by-side", "fullscreen"],
        showIcons: ["strikethrough", "code", "table", "horizontal-rule", "undo", "redo"],
        renderingConfig: {
          singleLineBreaks: false,
          codeSyntaxHighlighting: true,
        },
        shortcuts: {
          "toggleFullScreen": null,
          "toggleSideBySide": null,
        }
        ,
        previewRender: function (plainText, preview) {
          setTimeout(function () {
            preview.innerHTML = this.parent.markdown(plainText);
            Prism.highlightAll();
          }.bind(this), 1);
          return "Loading..."
        }
        ,
      })
    }
  }
</script>

<style scoped>

</style>
