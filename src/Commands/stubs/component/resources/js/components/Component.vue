<template>
  <div class="mx-4 pt-3">
    <h3>This is a custom component</h3>
    <p>You can edit this component in <code>/lyra-components/{{ component }}/resources/js/components/Component.vue</code></p>
  </div>
</template>

<script>
  export default {
    // Vue component
    methods: {
      getTest() {
        this.$root.enableLoader();
        this.$http.get('/components/{{ component }}/test').then((response) => {
          // Example request
        });
      }
    },
    mounted() {
      this.getTest();
    }
  }
</script>

<style scoped>
  /* Component styles */
</style>
