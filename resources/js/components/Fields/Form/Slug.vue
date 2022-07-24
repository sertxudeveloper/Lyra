<template>
  <div class="col-span-3 xl:col-span-2">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
      <input
        class="block border flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none pl-3 pr-2 py-2 rounded-md sm:text-sm w-full text-gray-700"
        :class="[ errors?.length ? errorClass : defaultClass ]"
        type="text" :name="field.key" v-model="value" :placeholder="field.name">
    </div>
    <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
  </div>
</template>

<script>
import slugify from 'slugify'

export default {
  name: "Slug",
  props: ['field', 'errors'],
  data() {
    return {
      defaultClass: 'border-gray-300',
      errorClass: 'border-red-400',

      dirty: false,
    }
  },
  computed: {
    from() {
      return this.$parent.resource.data.fields.find(field => field.key === this.field.from)
    },
    value: {
      get() {
        return this.field.value
      },
      set(value) {
        this.dirty = true
        this.field.value = slugify(value.toLowerCase(), this.field.separator)
      }
    },
  },
  watch: {
    from: {
      deep: true,
      handler(field) {
        if (!this.dirty) this.field.value = slugify(field.value.toLowerCase(), this.field.separator)
      }
    }
  },
}
</script>
