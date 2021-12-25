<template>
  <div class="col-span-3">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div v-if="field.placeholder" class="flex mt-1 sm:text-sm text-gray-500 w-full">
      <span class="whitespace-no-wrap font-mono">{{ field.placeholder }}</span>
    </div>
    <p v-else class="text-gray-300">&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "Text",
  props: ['field'],
}
</script>
