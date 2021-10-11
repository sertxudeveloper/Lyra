<template>
  <div class="col-span-3 md:col-span-2 xl:col-span-1">
    <label class="block text-sm font-medium text-gray-600" :for="field.key">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
      <input class="block border border-gray-300 flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none px-3 py-2 rounded-md sm:text-sm w-full text-gray-700"
             type="datetime-local" :name="field.key" step="1" v-model.lazy="value">
    </div>
  </div>
</template>

<script>
export default {
  name: "DateTime",
  props: ['field'],
  computed: {
    value: {
      set(value) {
        this.field.value = value
      },
      get() {
        if (!this.field.value) return ''

        let date = new Date(this.field.value)
        let ISOdate = date.toISOString()
        return ISOdate.substring(0, ISOdate.length - 1)
      }
    }
  }
}
</script>
