<template>
  <div>
    <router-link
      class="badge badge-primary mr-1"
      v-for="element in field.value" :key="element.key"
      :to="{name: 'show', params: {resourceName: field.path, resourceId: element.key}}">{{element.value}}
    </router-link>
  </div>
</template>

<script>
  export default {
    props: ['field'],
  }
</script>

<style scoped>
  .badge {
    font-size: 85%;
  }
</style>
