<template>
  <div v-if="$root.loader === false && disks !== null" class="pb-5 pt-3">
    <div class="row justify-content-between m-0">
      <div class="col-8">
        <Breadcrumb :key="$route.fullPath" @change-path="changePathEvent"></Breadcrumb>
      </div>
      <div class="col-3 align-self-center">
        <div class="form-group row m-0">
          <label for="selectDisk" class="col-sm-5 text-right col-form-label">Selected Disk:</label>
          <div class="col-sm-7 px-0">
            <select class="form-control" v-model="selectedDisk" id="selectDisk">
              <option :value="disk" v-for="disk in disks">{{disk}}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row m-0">
      <div class="col-3">
        <FolderTree :key="selectedDisk" @change-path="changePathEvent"></FolderTree>
      </div>
      <div class="col-9">
        <MediaViewer :key="$route.fullPath" @change-path="changePathEvent"></MediaViewer>
      </div>
    </div>
  </div>
</template>
<script>
  import Breadcrumb from './Media/Breadcrumb'
  import FolderTree from './Media/TreeFolder'
  import MediaViewer from './Media/MediaViewer'

  export default {
    components: {Breadcrumb, FolderTree, MediaViewer},
    data() {
      return {
        disks: null,
        selectedDisk: this.$route.query.disk,
        currentPath: this.$route.query.path ? this.$route.query.path : '/',
      }
    },
    methods: {
      getDisks: function () {
        this.$root.enableLoader();
        const path = this.$route.fullPath.split('?')[0];
        const query = this.$route.fullPath.split('?')[1];
        this.$http.get(`${path}/disks?${query}`).then((response) => {
          this.disks = response.data.disks;
          this.selectedDisk = response.data.selected;
          this.$router.push({query: {...this.$route.query, disk: this.selectedDisk, path: null}});
        });
      },
      changePathEvent: function (path) {
        this.currentPath = '/' + path;
        this.$router.push({query: {...this.$route.query, path}});
      },
    },
    watch: {
      '$route': function (from, to) {
        this.currentPath = to.query.path ? to.query.path : '/';
        this.selectedDisk = to.query.disk;
      }
    },
    beforeMount() {
      this.getDisks();
    }
  }
</script>
<style scoped>

</style>
