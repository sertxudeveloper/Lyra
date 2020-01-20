<template>
  <div :class="card.class">
    <div class="card">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-3 text-center">
            <span class="h2 text-muted mb-0">
              <i :class="card.icon"></i>
            </span>
          </div>
          <div class="col-auto">
            <h6 class="card-title text-uppercase text-muted mb-2">{{ card.title }}</h6>
            <span class="h4 mb-0">{{ formatValue(card.value) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['card'],
    methods: {
      numberValue(value) {
        const SUFFIXES = "kMGTPEZY";
        let i = -1;
        while ((value = value / 1000) >= 1) {
          i++;
        }
        if (i === -1) return (value * 1000);
        return (value * 1000).toFixed(2) + SUFFIXES[i];
      },
      formatValue(value) {
        if (!isNaN(value)) return this.numberValue(value);
        return value;
      },
    }
  }
</script>

<style scoped>

</style>
