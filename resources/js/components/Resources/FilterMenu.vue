<template>
  <!-- Filter tool -->
  <Dropdown>
    <template #trigger="{ toggle }">
      <button @click="toggle"
              class="bg-gray-200 cursor-pointer inline-flex focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 gap-1.5 gap-x-2 h-full items-center px-3 rounded text-gray-700">
        <svg width="19" height="19" viewBox="0 0 22 22" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M20.6236 0H1.37663C0.155245 0 -0.461013 1.48186 0.404334 2.34725L8.25 10.1946V18.2187C8.24999 18.4147 8.29188 18.6085 8.37287 18.7869C8.45385 18.9654 8.57206 19.1245 8.71956 19.2535L11.4696 21.659C12.3478 22.4275 13.75 21.8172 13.75 20.6243V10.1946L21.5959 2.34725C22.4595 1.48362 21.8475 0 20.6236 0ZM12.375 9.625V20.625L9.625 18.2187V9.625L1.375 1.375H20.625L12.375 9.625Z"/></svg>
        <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"/></svg>
      </button>
    </template>

    <DropdownMenu>
      <!-- Per Page -->
      <div>
        <div class="bg-gray-100 block font-medium px-4 py-2 text-gray-600 text-xs tracking-wider uppercase">Items per page</div>
        <div class="border border-gray-200 flex m-2 rounded-md focus-within:ring-1 focus-within:ring-blue-500">
          <select
              :value="perPage"
              @change="perPageChanged"
              class="bg-transparent mr-2 px-2 py-1 text-gray-700 text-sm w-full focus:outline-none">
            <option v-for="option in perPageOptions" :value="option">{{ option }}</option>
          </select>
        </div>
      </div>

      <!-- Soft Deletes -->
      <div v-if="softDeletes">
        <div class="bg-gray-100 block font-medium px-4 py-2 text-gray-600 text-xs tracking-wider uppercase">Trashed</div>
        <div class="border border-gray-200 flex m-2 rounded-md">
          <select
              :value="trashed"
              @change="trashedChanged"
              class="bg-transparent mr-2 px-2 py-1 text-gray-700 text-sm w-full">
            <option value="" selected>&mdash;</option>
            <option value="only">Only Trashed</option>
            <option value="with">With Trashed</option>
          </select>
        </div>
      </div>
    </DropdownMenu>
  </Dropdown>
</template>

<script>
export default {
  props: ['perPage', 'perPageOptions', 'softDeletes'],
  emits: ['perPageChanged', 'trashedChanged'],
  methods: {
    perPageChanged(event) {
      this.$emit('perPageChanged', event.target.value)
    },
    trashedChanged(event) {
      this.$emit('trashedChanged', event.target.value)
    },
  },
  computed: {
    trashed() {
      return this.$route.query.trashed
    }
  }
}
</script>
