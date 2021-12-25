<template>
  <div class="flex w-full max-w-sm mx-auto mt-4 overflow-hidden bg-white rounded-lg shadow-md">
    <div class="bg-red-500 flex flex-shrink-0 items-center justify-center w-12">
      <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z"></path></svg>
    </div>
    <div class="px-4 py-3 w-full">
      <div class="flex justify-between items-center mb-1">
        <span class="font-semibold text-red-500 leading-tight">{{ notification.title }}</span>
        <div class="flex flex-shrink-0 ml-4">
          <button @click="$emit('close')"
                  class="inline-flex text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-400">
            <span class="sr-only">Close</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" fill-rule="evenodd"></path></svg>
          </button>
        </div>
      </div>
      <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ notification.text }}</p>
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

