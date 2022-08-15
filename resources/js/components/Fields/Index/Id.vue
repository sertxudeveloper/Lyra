<template>
  <div>
    <template v-if="field.value">
      <span class="whitespace-no-wrap">{{ field.value }}</span>
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
