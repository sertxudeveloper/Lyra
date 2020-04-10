<template>
  <div class="checkbox">
    <label class="checkbox-container">
      <input type="checkbox" v-model="field.value" :id="field.column">
      <span class="checkmark"></span>
    </label>
  </div>
</template>

<script>
  export default {
    props: ['field', 'formData']
  }
</script>

<style scoped>
  .checkbox {
    height: 24px;
  }

  .checkbox-container {
    display: block;
    position: relative;
    padding-left: 35px;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    font-size: 15px;
    margin: 0;
  }
  .checkbox-container input[type=checkbox] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    width: auto;
  }
  .checkbox-container input[type=checkbox] + .checkmark {
    position: absolute;
    top: 1px;
    left: 0;
    height: 20px;
    width: 20px;
    border: 1px solid #BDBDBD;
    border-radius: 5px;
    transition: all 200ms;
  }
  .checkbox-container input[type=checkbox] + .checkmark:after {
    content: "";
    position: absolute;
    display: none;
    left: 6px;
    top: 2px;
    width: 6px;
    height: 11px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
  }
  .checkbox-container:hover input[type=checkbox] ~ .checkmark, .checkbox-container input[type=checkbox]:focus ~ .checkmark {
    background-color: #424242;
  }
  .checkbox-container input[type=checkbox]:checked ~ .checkmark {
    background-color: var(--primary);
    border-color: var(--primary);
  }
  .checkbox-container input[type=checkbox]:checked ~ .checkmark:after {
    display: block;
  }
</style>
