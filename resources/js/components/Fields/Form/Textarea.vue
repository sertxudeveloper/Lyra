<template>
  <div class="col-span-3 xl:col-span-2">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div class="flex mt-1 rounded-md shadow-sm">
      <textarea class="block border flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none pl-3 pr-2 py-2 rounded-md sm:text-sm w-full text-gray-700 min-h-[118px]"
                :class="[ errors?.length ? errorClass : defaultClass ]"
                rows="5" v-model.lazy="field.value" :placeholder="field.placeholer"></textarea>
    </div>
    <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: "Textarea",
  props: ['field', 'errors'],
  data() {
    return {
      defaultClass: 'border-gray-300',
      errorClass: 'border-red-400',
    }
  },
}
</script>
