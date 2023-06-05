<template>
  <div class="col-span-3">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div v-if="field.value" class="flex mt-1 sm:text-sm text-gray-700 w-full">
      <span class="whitespace-no-wrap">{{ value }} ({{ timezone }})</span>
    </div>
    <p v-else class="text-gray-400">&mdash;</p>
  </div>
</template>

<script>
import moment from 'moment-timezone';

export default {
  name: "DateTime",
  props: ['field'],
  computed: {
    value() {
      return moment(this.field.value).format('LLL');
    },
    timezone() {
      return moment.tz.guess()
    },
  }
}
</script>
