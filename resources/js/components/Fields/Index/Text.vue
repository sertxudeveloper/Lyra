<template>
  <div :class="`text-${field.textAlign}`">
    <template v-if="field.value">
      <div v-if="field.asHtml" v-html="field.value"></div>
      <span v-else class="whitespace-no-wrap">{{ field.value }}</span>
    </template>
    <p v-else class="text-gray-400">&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "Text",
  props: ['field'],
}
</script>
