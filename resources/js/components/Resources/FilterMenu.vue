<template>
  <!-- Filter tool -->
  <Dropdown>
    <template #trigger="{ toggle }">
<!--        class="bg-gray-200 cursor-pointer inline-flex focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 gap-1.5 gap-x-2 h-full items-center px-3 rounded text-gray-700">-->
      <button @click="toggle" class="btn-toolbar flex items-center space-x-2.5">
        <Icon name="filter" class="w-5" />
        <Icon name="chevron-down" class="w-3" />
      </button>
    </template>

    <DropdownMenu>
      <!-- Per Page -->
      <div>
        <div class="bg-gray-100 block font-medium px-4 py-2 text-gray-600 text-xs tracking-wider uppercase">Items per page</div>
        <div class="border border-gray-200 flex m-2 rounded-md focus-within:ring-1 focus-within:ring-blue-600">
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
