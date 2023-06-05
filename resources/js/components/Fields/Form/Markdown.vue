<template>
  <div class="col-span-3">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
<!--      <textarea class="block border flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none pl-3 pr-2 py-2 rounded-md sm:text-sm w-full text-gray-700 min-h-[118px]"
                :class="[ errors?.length ? errorClass : defaultClass ]"
                rows="5" v-model.lazy="field.value" :placeholder="field.placeholer"></textarea>-->

      <div ref="editor" class="border rounded-md w-full"
           :class="[ errors?.length ? errorClass : defaultClass ]">{{ initialValue }}</div>
    </div>
    <div class="mt-1 px-1 text-red-500 text-sm" v-for="error in errors">{{ error }}</div>
  </div>
</template>

<script>
import MarkdownEditor from '@sertxudeveloper/markdown-editor'

export default {
  name: "Markdown",
  props: ['field', 'errors'],
  data() {
    return {
      initialValue: this.field.value,
      defaultClass: 'border-gray-300',
      errorClass: 'border-red-400',
      editor: null,
    }
  },
  mounted() {
    this.editor = new MarkdownEditor(this.$refs.editor, {
      key: this.field.key,
      language: window.config.lang,
      placeholder: this.field.placeholder ?? '',
    })

    this.editor.on('change', () => {
      this.field.value = this.editor.getValue()
    })
  },
}
</script>
