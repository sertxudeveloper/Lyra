<template>
  <div class="bg-white flex flex-col p-4 rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
      <div class="font-semibold text-gray-500 text-sm tracking-wider uppercase">{{ data.label }}</div>
      <div class="bg-gray-200 bg-opacity-70 px-1 py-px rounded">
        <select class="bg-transparent flex outline-none pr-1 text-sm" v-model="data.selected" @change="updateCard">
          <option v-for="(label, key) in data.interval" :value="key">{{ label }}</option>
          <option value="12 hours">12 hours</option>
        </select>
      </div>
    </div>
    <div class="flex justify-between">
      <div class="text-4xl text-gray-800">{{ data.value }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SimpleCard",
  props: ['card'],
  data() {
    return {
      data: null
    }
  },
  beforeMount() {
    this.data = {...this.card}
  },
  methods: {
    updateCard() {
      this.$http.get(`/cards/${this.$route.params.resourceName}/${this.data.slug}?interval=${this.data.selected}`)
          .then(response => this.data = response.data)
    }
  }
}
</script>

<style scoped>

</style>
