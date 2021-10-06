<template>
  <div>
    <p v-if="field.value">{{ localizedDateTime }}</p>
    <p v-else>&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "DateTime",
  props: ['field'],
  computed: {
    localizedDateTime() {
      console.log(typeof this.field.value)
      let date = new Date(this.field.value)

      if (!this.field.forceLocale)
        return date.toLocaleString();

      return date.toLocaleString(this.field.forceLocale);
    },
  },
}
</script>
