<template>
  <div>
    <span>This is Dashboard {{ $route.params.id }}</span>
    <!--<component v-bind:is="currentTabComponent"></component>-->
  </div>
</template>
<script>
  // import axios from 'axios';
  import HttpService from '../HttpService'

  export default {
    data() {
      return {}
    },
    created: function () {
      HttpService.get('/user-agent')
        .then(function (response) {
          // print entire response
          console.log(response);

          // print a specific part of response
          console.log(response.data['user-agent']);

        })
        .catch(function (error) {
          // if an error occurs, print that error
          console.log(error);
        });
    }
  }
</script>
<style scoped>

</style>