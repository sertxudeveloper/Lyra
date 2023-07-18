<template>
  <table class="w-full border-b border-gray-200">
    <thead class="bg-gray-100">
      <tr class="uppercase text-left text-xs tracking-wider text-gray-500">
        <!-- Checkbox -->
        <th class="px-6 py-4 w-0" scope="col">&nbsp;</th>

        <!-- Resource columns -->
        <th v-for="field in resources.data[0].fields"
            class="px-6 py-4 font-medium"
            :class="[{ 'cursor-pointer': field.sortable }, `text-${field.align}`]"
            scope="col"
            @click="$emit('order', field)">
          <div class="inline-flex space-x-2 items-center">
            <span>{{ field.name }}</span>
            <div v-if="field.sortable" class="text-gray-400">

              <!-- Sort asc icon -->
              <template v-if="field.order === 'asc'">
                <Icon name="sort-asc" class="w-3" />
              </template>

              <!-- Sort desc icon -->
              <template v-else-if="field.order === 'desc'">
                <Icon name="sort-desc" class="w-3" />
              </template>

              <!-- Sort none icon -->
              <template v-else>
                <Icon name="sort-none" class="w-3" />
              </template>
            </div>
          </div>
        </th>

        <!-- Actions -->
        <th class="px-6 py-4 w-0" scope="col">&nbsp;</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 text-gray-600">
      <DatatableRow
          v-for="resource in resources.data"
          :key="resource.key"
          :resource="resource"
          :checked="selectedResources.includes(resource.key)"
          @select="$emit('select', resource.key)"
          @delete="$emit('delete', resource.key)"
          @restore="$emit('restore', resource.key)"
      />
    </tbody>
  </table>
</template>

<script>
export default {
  props: ['resources', 'selectedResources'],
  emits: ['order', 'select', 'delete', 'restore'],
}
</script>
