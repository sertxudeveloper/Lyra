<template>
  <div class="bg-white mt-4 overflow-hidden pointer-events-auto ring-1 ring-black ring-opacity-5 rounded-lg shadow-lg w-full">
    <div class="p-4">
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg aria-hidden="true" class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
          <p class="font-semibold text-gray-800 leading-tight">{{ notification.title }}</p>
          <p class="text-sm text-gray-600 mt-1">{{ notification.text }}</p>
        </div>
        <div class="flex flex-shrink-0 ml-4">
          <button @click="$emit('close')"
              class="inline-flex text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
            <span class="sr-only">Close</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" fill-rule="evenodd"></path></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Success",
  props: ['notification'],
  emits: ['close'],
}
</script>

<style scoped>

</style>
