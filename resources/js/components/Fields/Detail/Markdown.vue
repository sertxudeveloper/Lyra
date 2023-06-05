<template>
  <div class="col-span-3">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div v-if="field.value" class="flex mt-1 sm:text-sm text-gray-700 w-full relative">
      <div class="w-full"
           :style="'max-height: ' + (contentHeight > maxHeight && showMore ? 'unset' : maxHeight + 'px')"
           :class="{ 'overflow-y-clip overflow-x-visible': contentHeight > maxHeight && !showMore }">
        <div class="whitespace-no-wrap markdown-preview w-full" ref="content" v-html="output"></div>

        <!-- Show less button -->
        <template v-if="contentHeight > maxHeight">
          <div v-show="showMore" x-cloak class="w-full">
            <div class="h-8 bg-gradient-to-b from-transparent to-white">&nbsp;</div>
            <button class="pb-2 pt-2.5 bg-white text-blue-600 text-center font-medium w-full"
                    @click="showMore = !showMore">
              <span>Show less</span>
            </button>
          </div>
        </template>
      </div>

      <!-- Show more button -->
      <template v-if="contentHeight > maxHeight">
        <div v-show="!showMore"
             class="w-full absolute bottom-0 left-0">
          <div class="h-8 bg-gradient-to-b from-transparent to-white">&nbsp;</div>
          <button class="pb-4 pt-2.5 bg-white text-blue-600 text-center font-medium w-full"
                  @click="showMore = !showMore">
            <span>Show more</span>
          </button>
        </div>
      </template>
    </div>
    <p v-else class="text-gray-400">&mdash;</p>
  </div>
</template>

<script>
import DOMPurify from 'dompurify';
import {marked} from 'marked';

export default {
  name: "Markdown",
  props: ['field'],
  setup() {
    marked.setOptions({gfm: true});
  },
  data() {
    return {
      contentHeight: null,
      maxHeight: 200,
      showMore: false,
    };
  },
  mounted() {
    this.contentHeight = this.$refs.content.clientHeight;
  },
  computed: {
    output() {
      return DOMPurify.sanitize(marked.parse(this.field.value));
    },
  },
}
</script>

<style lang="scss">
.markdown-preview {
  * {
    @apply my-2;
  }

  h1 {
    @apply text-3xl font-bold;
  }

  h2 {
    @apply text-2xl font-bold;
  }

  h3 {
    @apply text-xl font-bold;
  }

  h4 {
    @apply text-lg font-bold;
  }

  h5 {
    @apply text-base italic font-bold;
  }

  p {
    @apply text-base;
  }

  a {
    @apply text-blue-600 underline;
  }

  ul {
    @apply list-disc;
  }

  ol {
    @apply list-decimal;
  }

  li {
    @apply list-inside;
  }
}
</style>
