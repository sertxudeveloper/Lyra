<template>
  <th v-for="field in header"
      @click="sort(field)"
      scope="col" class="px-6 py-4 font-medium"
      :class="[ field.sortable ? 'cursor-pointer' : '' ]">
    <div class="flex space-x-2 items-center">
      <span>{{ field.name }}</span>
      <div v-if="field.sortable" class="text-gray-400">
        <template v-if="field.order === 'asc'">
          <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13.516 5.06606L8.73323 0.283346C8.35544 -0.0944486 7.74454 -0.0944486 7.37076 0.283346L2.58403 5.06606C1.97714 5.67295 2.40719 6.71389 3.26727 6.71389H12.8327C13.6928 6.71389 14.1228 5.67295 13.516 5.06606Z"/></svg>
        </template>
        <template v-else-if="field.order === 'desc'">
          <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.26727 9.28613H12.8327C13.6928 9.28613 14.1228 10.3271 13.516 10.934L8.73323 15.7167C8.35544 16.0945 7.74454 16.0945 7.37076 15.7167L2.58403 10.934C1.97714 10.3271 2.40719 9.28613 3.26727 9.28613Z"/></svg>
        </template>
        <template v-else>
          <svg height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.26727 9.28611H12.8327C13.6928 9.28611 14.1228 10.3271 13.516 10.9339L8.73323 15.7167C8.35544 16.0944 7.74454 16.0944 7.37076 15.7167L2.58403 10.9339C1.97714 10.3271 2.40719 9.28611 3.26727 9.28611ZM13.516 5.06606L8.73323 0.283346C8.35544 -0.0944486 7.74454 -0.0944486 7.37076 0.283346L2.58403 5.06606C1.97714 5.67295 2.40719 6.71389 3.26727 6.71389H12.8327C13.6928 6.71389 14.1228 5.67295 13.516 5.06606Z"/></svg>
        </template>
      </div>
    </div>
  </th>
</template>

<script>
export default {
  name: "TableHeader",
  props: ['header'],
  emits: ['updated'],
  methods: {
    sort(field) {
      if (!field.sortable) return null

      let URLSearch = new URLSearchParams(location.search)

      if (!URLSearch.has('sortBy') || !URLSearch.has('sortOrder')) {
        URLSearch.delete('sortBy')
        URLSearch.delete('sortOrder')
      }

      let sortBy = URLSearch.get('sortBy')
      sortBy = (sortBy) ? sortBy.split(',') : []

      let sortOrder = URLSearch.get('sortOrder')
      sortOrder = (sortOrder) ? sortOrder.split(',') : []

      let index = sortBy.findIndex(element => element === field.key)
      if (index !== -1) {
        let order = sortOrder[index]
        if (order === 'desc') {
          sortBy.splice(index, 1)
          sortOrder.splice(index, 1)
        } else {
          sortOrder[index] = 'desc'
        }
      } else {
        sortBy.push(field.key)
        sortOrder.push('asc')
      }

      sortBy = sortBy.join(',')
      sortOrder = sortOrder.join(',')

      this.$router.push({ query: { ...this.$route.query, sortBy: sortBy, sortOrder: sortOrder }})
      this.$emit('updated', { sortBy: sortBy, sortOrder: sortOrder })
    }
  }
}
</script>

