<template>
  <div class="col-span-3 xl:col-span-2">
    <label class="block text-sm font-medium text-gray-600" :for="field.key">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
      <input class="block border border-gray-300 flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none px-3 py-2 rounded-md sm:text-sm w-full text-gray-700"
             type="text" :name="field.key" v-model.lazy="field.value" :placeholder="field.name">
    </div>
  </div>
</template>

<script>
export default {
  name: "Text",
  props: ['field'],
}
</script>
