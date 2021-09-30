<template>
  <h1>{{ $route.params.resourceName }}</h1>
</template>

<script>
export default {
  name: "Resource",
  data() {
    return {
      resources: {},
      cards: [],
    }
  },
  mounted() {
    this.$http.get(`/resource/${this.$route.params.resourceName}`)
      .then(response => this.resources = response.data)

    this.$http.get(`/cards/${this.$route.params.resourceName}`)
        .then(response => this.cards = response.data)
  }
}
</script>

<style scoped>

</style>
