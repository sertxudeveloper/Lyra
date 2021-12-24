<template>
  <div class="col-span-3 md:col-span-2 xl:col-span-1">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
      <input class="block border flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none px-3 py-2 rounded-md sm:text-sm w-full text-gray-700"
             :class="[ errors?.length ? errorClass : defaultClass ]"
             type="datetime-local" :name="field.key" step="1" v-model.lazy="value">
    </div>
    <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: "DateTime",
  props: ['field', 'errors'],
  data() {
    return {
      defaultClass: 'border-gray-300',
      errorClass: 'border-red-400',
    }
  },
  computed: {
    value: {
      set(value) {
        this.field.value = value
      },
      get() {
        if (!this.field.value) return ''

        let date = new Date(this.field.value)
        let IsoDate = date.toISOString()
        return IsoDate.substring(0, IsoDate.length - 1)
      }
    }
  }
}
</script>
