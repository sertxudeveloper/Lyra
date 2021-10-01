<template>
  <div class="bg-white flex flex-col justify-between p-4 rounded-lg shadow">
    <div class="flex flex-col">
      <div class="flex items-center justify-between mb-2">
        <div class="font-semibold text-gray-500 text-sm tracking-wider uppercase">{{ card.label }}</div>
        <div class="text-gray-400 text-sm tracking-wide">{{ card.total }} total</div>
      </div>
      <div class="gap-y-1 gap-x-4 grid grid-cols-2">
        <div class="flex items-center text-gray-500 text-xs" v-for="group in card.values">
          <div class="flex flex-shrink-0 h-2.5 mr-1.5 rounded-full w-2.5" :style="`background-color: ${group.color}`"></div>
          <div class="flex justify-between w-full">
            <div class="">{{ group.label }}</div>
            <div>{{ group.value }} - ({{ group.percent.toFixed(2) }}%)</div>
          </div>
        </div>
      </div>
    </div>
    <div class="h-2 mt-3 overflow-hidden rounded-full w-full flex">
      <div class="h-full" v-for="group in card.values" :style="`width: ${group.percent}%; background-color: ${group.color}`"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PartitionCard",
  props: ['card'],
}
</script>

<style scoped>

</style>
