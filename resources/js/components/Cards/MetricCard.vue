<template>
  <div class="bg-white flex flex-col p-4 rounded-lg shadow justify-between">
    <div class="flex items-center justify-between mb-4">
      <div class="font-semibold text-gray-500 text-sm tracking-wider uppercase">{{ data.label }}</div>
      <div class="bg-gray-200 bg-opacity-70 px-1 py-px rounded">
        <select class="bg-transparent flex outline-none pr-1 text-sm text-gray-700" v-model="data.range" @change="updateCard">
          <option v-for="(label, key) in data.ranges" :value="key">{{ label }}</option>
        </select>
      </div>
    </div>
    <div class="flex h-full justify-between">
      <div class="flex items-end text-4xl text-gray-800">{{ data.value }}</div>
      <div>
        <div class="border font-semibold px-2 rounded-full text-sm" :class="diffClass">{{ difference }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MetricCard",
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
      this.$http.get(`/cards/${this.$route.params.resourceName}/${this.data.slug}?range=${this.data.range}`)
          .then(response => this.data = response.data)
    }
  },
  computed: {
    difference() {
      return [this.data.difference.slice(0, 1), this.data.difference.slice(1)].join(' ');
    },
    diffClass() {
      if (this.data.difference.slice(0, 1) === '-') {
        return 'bg-red-50 border-red-300 text-red-700'
      } else if (this.data.difference.slice(0, 1) === '+') {
        return 'bg-green-50 border-green-300 text-green-700'
      } else {
        return 'bg-gray-50 border-gray-300 text-gray-700'
      }
    }
  }
}
</script>

<style scoped>

</style>
