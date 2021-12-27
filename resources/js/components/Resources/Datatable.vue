<template>
  <table class="w-full border-b border-gray-200">
    <thead class="bg-gray-100">
      <tr class="uppercase text-left text-xs tracking-wider text-gray-500">
        <!-- Checkbox -->
        <th class="px-6 py-4" scope="col">&nbsp;</th>

        <!-- Resource columns -->
        <th v-for="field in resources.header"
            class="px-6 py-4 font-medium"
            :class="{ 'cursor-pointer': field.sortable }"
            scope="col"
            @click="$emit('order', field)">
          <div class="flex space-x-2 items-center">
            <span>{{ field.name }}</span>
            <div v-if="field.sortable" class="text-gray-400">

              <!-- Sort asc icon -->
              <template v-if="field.order === 'asc'">
                <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13.516 5.06606L8.73323 0.283346C8.35544 -0.0944486 7.74454 -0.0944486 7.37076 0.283346L2.58403 5.06606C1.97714 5.67295 2.40719 6.71389 3.26727 6.71389H12.8327C13.6928 6.71389 14.1228 5.67295 13.516 5.06606Z"/></svg>
              </template>

              <!-- Sort desc icon -->
              <template v-else-if="field.order === 'desc'">
                <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.26727 9.28613H12.8327C13.6928 9.28613 14.1228 10.3271 13.516 10.934L8.73323 15.7167C8.35544 16.0945 7.74454 16.0945 7.37076 15.7167L2.58403 10.934C1.97714 10.3271 2.40719 9.28613 3.26727 9.28613Z"/></svg>
              </template>

              <!-- Sort none icon -->
              <template v-else>
                <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.26727 9.28611H12.8327C13.6928 9.28611 14.1228 10.3271 13.516 10.9339L8.73323 15.7167C8.35544 16.0944 7.74454 16.0944 7.37076 15.7167L2.58403 10.9339C1.97714 10.3271 2.40719 9.28611 3.26727 9.28611ZM13.516 5.06606L8.73323 0.283346C8.35544 -0.0944486 7.74454 -0.0944486 7.37076 0.283346L2.58403 5.06606C1.97714 5.67295 2.40719 6.71389 3.26727 6.71389H12.8327C13.6928 6.71389 14.1228 5.67295 13.516 5.06606Z"/></svg>
              </template>
            </div>
          </div>
        </th>

        <!-- Actions -->
        <th class="px-6 py-4" scope="col">&nbsp;</th>
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
