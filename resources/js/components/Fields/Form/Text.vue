<template>
  <div class="col-span-3 xl:col-span-2">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
      <input
        class="block border flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none pl-3 pr-2 py-2 rounded-md sm:text-sm w-full text-gray-700"
        :class="[ errors?.length ? errorClass : defaultClass ]"
        type="text" :name="field.key" v-model="field.value" :placeholder="field.name">
    </div>
    <div class="mt-1 px-1 text-red-500 text-sm" v-for="error in errors">{{ error }}</div>
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
