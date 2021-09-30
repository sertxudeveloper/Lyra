<template>
  <div class="p-4">
    <h1 class="capitalize mb-4 text-3xl text-gray-800">{{ $route.params.resourceName }}</h1>
    <div v-if="cards.length" class="flex flex-wrap gap-4 mb-4">
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
