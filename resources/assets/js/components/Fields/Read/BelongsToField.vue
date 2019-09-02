<template>
  <router-link
    :to="{name: 'show', params: {resourceName: field.path, resourceId: field.value.key}}">{{field.value.value}}
  </router-link>
</template>

<script>
  export default {
    props: ['field']
  }
</script>

<style scoped>

</style>
