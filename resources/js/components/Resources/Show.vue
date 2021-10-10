<template>
  <h1>Show</h1>
  <pre>{{ $route }}</pre>
</template>

<script>
export default {
  name: "Show",
  data() {
    return {
      resource: {},
    }
  },
  mounted() {
    this.getResource()
  },
  methods: {
    getResource() {
      this.$http.get(`/resource/${this.$route.params.resourceName}/${this.$route.params.resourceId}`)
          .then(response => {
            this.resource = response.data
          })
    },
  }
}
</script>

<style scoped>

</style>
