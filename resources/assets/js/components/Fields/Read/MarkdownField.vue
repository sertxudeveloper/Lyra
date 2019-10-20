<template>
  <div class="markdown-body">
    <template v-if="outputHTML.length > limitChar">
      <template v-if="readMoreActive">
        <div class="my-3 editor-preview-active" v-html="outputHTML"></div>
        <div class="text-right">
          <button class="btn btn-outline-primary" @click="readLess">Read Less</button>
        </div>
      </template>
      <template v-else>
        <div class="my-3 editor-preview-active" v-html="excerpt"></div>
        <div class="text-right">
          <button class="btn btn-outline-primary" @click="readMore">Read More</button>
        </div>
      </template>
    </template>
    <template v-else>
      <div class="my-3 editor-preview-active" v-html="outputHTML"></div>
    </template>
  </div>
</template>

<script>
  import marked from 'marked'

  export default {
    data() {
      return {
        limitChar: 500,
        readMoreActive: false,
      }
    },
    props: ['field'],
    computed: {
      outputHTML: function () {
        return marked(this.field.value)
      },
      excerpt: function () {
        return this.outputHTML.substr(0, this.limitChar) + "...";
      },
    },
    methods: {
      readLess: function () {
        this.readMoreActive = false
      },
      readMore: function () {
        this.readMoreActive = true
      }
    }
  }
</script>

<style scoped>

</style>
