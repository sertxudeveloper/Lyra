<template>
  <div class="bg-white flex flex-col p-4 rounded-lg shadow justify-between">
    <div class="flex items-center justify-between mb-4">
      <div class="font-semibold text-gray-500 text-sm tracking-wider uppercase">{{ card.label }}</div>
    </div>
    <div class="flex justify-between">
      <div class="text-4xl text-gray-800">{{ card.value }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SimpleCard",
  props: ['card'],
}
</script>

