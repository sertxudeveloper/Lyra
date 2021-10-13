<template>
  <th scope="col" v-for="field in fields" class="px-6 py-4 font-medium">
    <div class="flex space-x-2 items-center">
      <span>{{ field.name }}</span>
      <div v-if="field.sortable" class="text-gray-400">
        <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.26727 9.28611H12.8327C13.6928 9.28611 14.1228 10.3271 13.516 10.9339L8.73323 15.7167C8.35544 16.0944 7.74454 16.0944 7.37076 15.7167L2.58403 10.9339C1.97714 10.3271 2.40719 9.28611 3.26727 9.28611ZM13.516 5.06606L8.73323 0.283346C8.35544 -0.0944486 7.74454 -0.0944486 7.37076 0.283346L2.58403 5.06606C1.97714 5.67295 2.40719 6.71389 3.26727 6.71389H12.8327C13.6928 6.71389 14.1228 5.67295 13.516 5.06606Z"/></svg>
      </div>
    </div>
  </th>
</template>

<script>
export default {
  name: "TableHeader",
  props: ['resource'],
  computed: {
    fields() {
      if (!this.resource.length) return []

      return this.resource[0].fields ?? []
    }
  }
}
</script>

<style scoped>

</style>
