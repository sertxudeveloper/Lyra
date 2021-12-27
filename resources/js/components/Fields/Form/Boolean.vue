<template>
  <div class="col-span-3 xl:col-span-2">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div class="flex mt-1">
      <input type="checkbox" :name="field.key" v-model.lazy="field.value" class="block cursor-pointer h-4 w-4">
    </div>
    <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: "Text",
  props: ['field', 'errors'],
  data() {
    return {
      defaultClass: 'border-gray-300',
      errorClass: 'border-red-400',
    }
  },
}
</script>
