<template>
  <router-view />
</template>

<script>
export default {
  name: "App",
  components: {  },
  setup() {
    const apiRoute = document.querySelector('meta[name="lyra-api-route"]').content
    const webRoute = document.querySelector('meta[name="lyra-web-route"]').content

    return { apiRoute, webRoute }
  },
  data() {
    return {
    }
  },
  beforeMount() {
  }
}
</script>

<style scoped>

</style>
