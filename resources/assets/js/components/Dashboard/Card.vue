<template>
  <div class="col-12 col-lg-6 col-xl">
    <div class="card">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-3 text-center">
            <span class="h2 text-muted mb-0"><i :class="card.icon"></i></span>
          </div>
          <template v-if="card.value">
            <div class="col-auto">
              <h6 class="card-title text-uppercase text-muted mb-2">{{ card.title }}</h6>
              <span class="h4 mb-0">{{ formatValue(card.value) }}</span>
            </div>
          </template>
          <template v-else>
            <div class="col-auto">
              <h6 class="card-title text-uppercase text-muted mb-2">{{ card.title }}</h6>
              <div class="align-items-baseline d-flex">
                <span data-v-f7d48b7c="" class="h4 mb-0">{{ card.primary_value }}</span>
                <span data-v-f7d48b7c="" class="mb-0 ml-1 small text-muted">({{ card.secondary_value }})</span>
                <span class="card-value-percentage mb-0 ml-3" :class="classObject(card.secondary_value, card.primary_value)">
                  {{ getPercentageChange(card.secondary_value, card.primary_value) }}%
                </span>
              </div>
            </div>
          </template>
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
      getPercentageChange(oldNumber, newNumber) {
        let decreaseValue = newNumber - oldNumber;
        return ((decreaseValue / newNumber) * 100).toFixed(0);
      },
      formatValue(value) {
        if (!isNaN(value)) return this.numberValue(value);
        return value;
      },
      classObject(oldNumber, newNumber) {
        return {
          'text-success': newNumber > oldNumber,
          'text-danger': newNumber < oldNumber,
          'text-secondary': newNumber === oldNumber,
        }
      }
    }
  }
</script>

<style scoped>
  .card-title {
    font-size: 15px;
  }

  .card-value-percentage {
    padding: 3px;
    margin-left: 15px;
  }
</style>
