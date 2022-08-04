<template>
  <div class="col-span-3">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div v-if="field.value" class="flex mt-1 sm:text-sm text-gray-700 w-full">
      <span class="whitespace-no-wrap markdown-preview" v-html="output"></span>
    </div>
    <p v-else class="text-gray-300">&mdash;</p>
  </div>
</template>

<script>
import DOMPurify from 'dompurify';
import { marked } from 'marked';

export default {
  name: "Markdown",
  props: ['field'],
  setup() {
    marked.setOptions({ gfm: true });
  },
  computed: {
    output() {
      return DOMPurify.sanitize(marked.parse(this.field.value));
    },
  },
}
</script>
