<template>
  <div class="flex w-full max-w-sm mx-auto mt-4 overflow-hidden bg-white rounded-lg shadow-md">
    <div class="bg-red-500 flex flex-shrink-0 items-center justify-center w-12">
      <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z"></path></svg>
    </div>
    <div class="px-4 py-2">
      <span class="font-semibold text-red-500">{{ notification.title }}</span>
      <p class="text-sm text-gray-600">{{ notification.text }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "Error",
  props: ['notification'],
  emits: ['close'],
}
</script>

<style scoped>

</style>
