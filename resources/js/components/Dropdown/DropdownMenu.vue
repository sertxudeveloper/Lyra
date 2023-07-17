<template>
  <div
      aria-labelledby="menu-button"
      aria-orientation="vertical"
      class="origin-top-right absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-20"
      role="menu"
      tabindex="-1">
    <slot/>
  </div>
</template>

<script>
export default {
    name: "Dropdown Menu",
}
</script>

