<template>
  <div>
    <p v-if="field.value" :title="timezone">{{ value }}</p>
    <p v-else class="text-gray-400">&mdash;</p>
  </div>
</template>

<script>
import moment from "moment-timezone";

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
