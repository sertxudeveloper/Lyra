<template>
  <Sidebar />
</template>

<script>
import Sidebar from "./Sidebar";

export default {
  name: "App",
  components: { Sidebar },
  data() {
    return {
      apiRoute: null,
      webRoute: null,
    }
  },
  beforeMount() {
    this.apiRoute = document.querySelector('meta[name="lyra-api-route"]').content
    this.webRoute = document.querySelector('meta[name="lyra-web-route"]').content
  }
}
</script>

<style scoped>

</style>
