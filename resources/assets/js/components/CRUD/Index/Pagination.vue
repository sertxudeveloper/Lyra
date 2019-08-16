<template>
  <nav class="pagination is-centered is-rounded my-2" role="navigation" aria-label="pagination">
    <button class="mx-1 btn btn-link pagination-previous" v-on:click="changePage(1)"
            :disabled="pagination.current_page <= 1"><i class="fas fa-angle-double-left"></i></button>
    <button class="mx-1 btn btn-link pagination-previous" v-on:click="changePage(pagination.current_page - 1)"
            :disabled="pagination.current_page <= 1"><i class="fas fa-angle-left"></i></button>
    <ul class="d-flex list-unstyled m-0 pagination-list">
      <li v-for="page in pages">
        <button class="mx-1 btn pagination-link" :class="isCurrentPage(page) ? 'btn-primary' : 'btn-link'"
                v-on:click="changePage(page)">{{ page }}
        </button>
      </li>
    </ul>
    <button class="mx-1 btn btn-link pagination-next" v-on:click="changePage(pagination.current_page + 1)"
            :disabled="pagination.current_page >= pagination.last_page"><i class="fas fa-angle-right"></i></button>
    <button class="mx-1 btn btn-link pagination-next" v-on:click="changePage(pagination.last_page)"
            :disabled="pagination.current_page >= pagination.last_page"><i class="fas fa-angle-double-right"></i>
    </button>
  </nav>
</template>

<script>
  export default {
    props: ['pagination', 'offset'],
    methods: {
      isCurrentPage(page) {
        return this.pagination.current_page === page;
      },
      changePage(page) {
        if (page > this.pagination.last_page) page = this.pagination.last_page;

        this.$router.push({query: {...this.$route.query, page: page}});
        this.$emit('clear-resource');
        this.$emit('get-resource');
      }
    },
    computed: {
      pages() {
        let pages = [];
        let from = this.pagination.current_page - Math.floor(this.offset / 2);
        if (from < 1) {
          from = 1;
        }
        let to = from + this.offset - 1;
        if (to > this.pagination.last_page) {
          to = this.pagination.last_page;
        }
        while (from <= to) {
          pages.push(from);
          from++;
        }
        return pages;
      }
    }
  }
</script>

<style scoped>
  .btn-link {
    color: var(--dark);
  }
</style>
