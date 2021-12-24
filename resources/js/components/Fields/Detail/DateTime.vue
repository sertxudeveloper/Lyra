<template>
  <div class="col-span-3 md:col-span-2 xl:col-span-1">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div v-if="field.value" class="flex mt-1 sm:text-sm text-gray-700 w-full">
      <span class="whitespace-no-wrap">{{ value }} ({{ timezone }})</span>
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
      return new Date(this.field.value).toLocaleString(this.locale, {timeZone: this.timezone})
    },
    timezone() {
      return Intl.DateTimeFormat().resolvedOptions().timeZone
    },
    locale() {
      return Intl.DateTimeFormat().resolvedOptions().locale
    }
  }
}
</script>
