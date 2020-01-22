<template>
  <div :class="card.class" class="px-2">
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
            <div class="align-items-baseline d-flex">
              <span data-v-f7d48b7c="" class="h4 mb-0">{{ card.value }}</span>
              <!--<span data-v-f7d48b7c="" class="mb-0 ml-1 small text-muted">({{ card.secondary_value }})</span>-->
              <span class="card-value-percentage mb-0 ml-3" :class="classObject(card.difference)">
                {{card.difference}}%
              </span>
            </div>
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
      classObject(difference) {
        return {
          'text-success': difference > 0,
          'text-danger': difference < 0,
          'text-secondary': difference === 0,
        }
      },
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
