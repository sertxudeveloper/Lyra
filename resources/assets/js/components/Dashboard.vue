<template>
  <div class="mx-lg-4 pb-2 pt-4 dashboard">
    <template v-if="dashboard">
      <div class="row" v-for="row in dashboard">
        <component v-for="(card, key) in row"
                   :key="key" :is="card.component" :card.sync="card"></component>
      </div>
    </template>
  </div>
</template>
<script>
  import CardSimple from './Cards/CardSimple'
  import CardMetrics from './Cards/CardMetrics'
  import CardOrderedList from './Cards/CardOrderedList'

  export default {
    components: {CardSimple, CardMetrics, CardOrderedList},
    data() {
      return {
        dashboard: null
      }
    },
    methods: {
      getDashboard: function () {
        this.$root.enableLoader();
        this.$http.get(this.$route.fullPath).then((response) => this.dashboard = response.data);
      },
    },
    computed: {},
    beforeMount: function () {
      this.getDashboard();
    },
  };
</script>
<style scoped>
  .dashboard .row {
    margin-bottom: 15px;
  }
</style>
