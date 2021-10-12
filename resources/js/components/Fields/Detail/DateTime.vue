<template>
  <div class="col-span-3 md:col-span-2 xl:col-span-1">
    <label class="block text-sm font-medium text-gray-600" :for="field.key">{{ field.name }}</label>
    <div v-if="field.value" class="flex mt-1 sm:text-sm text-gray-700 w-full">
      <span class="whitespace-no-wrap">{{ value }}</span>
    </div>
    <p v-else class="text-gray-300">&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "DateTime",
  props: ['field'],
  computed: {
    value() {
      if (!this.field.value) return ''

      let date = new Date(this.field.value)
      let IsoDate = date.toISOString()
      return IsoDate.substring(0, IsoDate.length - 1)
    }
  }
}
</script>
