<template>
  <div>
    <template v-if="hasValue">
      <span class="whitespace-no-wrap">{{ field.value }}</span>
    </template>
    <p v-else class="text-gray-300">&mdash;</p>
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
