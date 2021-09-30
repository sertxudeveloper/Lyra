<template>
  <div class="lg:p-4">
    <h1 class="capitalize mb-4 text-3xl text-gray-800">{{ $route.params.resourceName }}</h1>
    <div v-if="cards.length" class="gap-4 grid grid-cols-1 md:grid-cols-2 mb-4 xl:grid-cols-4">
      <component v-for="card in cards" :is="card.component" :card="card"></component>
    </div>
  </div>
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
