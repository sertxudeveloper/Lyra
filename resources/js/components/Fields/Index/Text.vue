<template>
  <div :class="`text-${field.textAlign}`">
    <template v-if="hasValue">
      <div v-if="field.asHtml" v-html="field.value"></div>
      <span v-else class="whitespace-no-wrap">{{ field.value }}</span>
    </template>
    <p v-else>&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "Text",
  props: ['field'],
  computed: {
    hasValue() {
      return this.field.value !== null
    },
  },
}
</script>
