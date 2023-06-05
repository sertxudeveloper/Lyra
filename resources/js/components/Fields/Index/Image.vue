<template>
  <div :class="`text-${field.textAlign}`">
    <template v-if="field.files.length">
      <img :src="field.files[0]" class="h-20">
    </template>
    <p v-else class="text-gray-400">&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "Image",
  props: ['field'],
}
</script>
