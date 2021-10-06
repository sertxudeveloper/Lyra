<template>
  <div>
    <nav v-if="meta.links">
      <div v-for="link in meta.links"
          :class="{ 'active': link.active }">
        <span v-html="link.label"></span>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: "Pagination",
  props: ['meta'],
}
</script>

<style scoped>

</style>
