<template>
  <div class="col-span-3">
    <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
    <div v-if="field.files.length" class="flex mt-1 sm:text-sm text-gray-700 w-full">
      <div class="gap-4 grid mb-2 items-center"
           :class="[ field.multiple ? 'grid-cols-3' : 'grid-cols-1' ]">
        <div v-for="image in field.files">
          <img :src="image" class="border border-gray-300">
        </div>
      </div>
    </div>
    <p v-else class="text-gray-300">&mdash;</p>
  </div>
</template>

<script>
export default {
  name: "Image",
  props: ['field'],
}
</script>
