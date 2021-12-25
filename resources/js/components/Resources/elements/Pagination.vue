<template>
  <div class="text-gray-500 text-sm">{{ meta.from }} - {{ meta.to }} of {{ meta.total }}</div>
  <div>
    <nav v-if="meta.links" class="-space-x-px inline-flex rounded-md shadow-sm">
      <div v-for="link in meta.links">
        <div class="h-full" @click="$emit('changePage', link.page)">

          <!-- Page links -->
          <div class="bg-white inline-flex items-center border text-sm font-medium relative cursor-pointer select-none px-4 py-2"
               :class="[ link.active ? activeClass : notActiveClass ]"
               v-if="!isNaN(link.label)">
            <span>{{ link.label }}</span>
          </div>

          <!-- Go to first -->
          <div class="bg-white inline-flex items-center border text-sm font-medium relative cursor-pointer select-none px-3 h-full rounded-l-lg"
               :class="[ link.active ? activeClass : notActiveClass ]"
               v-else-if="link.label === 'First'">
            <svg class="w-3 h-3" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13.9736 15.8726L6.40809 8.3071C6.23829 8.13729 6.23829 7.86271 6.40809 7.6929L13.9736 0.127357C14.1434 -0.0424523 14.418 -0.0424523 14.5878 0.127357L15.3032 0.842723C15.473 1.01253 15.473 1.28712 15.3032 1.45693L8.77458 8L15.3032 14.5431C15.473 14.7129 15.473 14.9875 15.3032 15.1573L14.5878 15.8726C14.418 16.0425 14.1434 16.0425 13.9736 15.8726V15.8726ZM8.8071 15.8726L9.52247 15.1573C9.69228 14.9875 9.69228 14.7129 9.52247 14.5431L2.99385 8L9.52247 1.45693C9.69228 1.28712 9.69228 1.01253 9.52247 0.842723L8.8071 0.127357C8.63729 -0.0424523 8.36271 -0.0424523 8.1929 0.127357L0.627357 7.6929C0.457548 7.86271 0.457548 8.13729 0.627357 8.3071L8.1929 15.8726C8.36271 16.0425 8.63729 16.0425 8.8071 15.8726Z"/></svg>
          </div>

          <!-- Go to last -->
          <div class="bg-white inline-flex items-center border text-sm font-medium relative cursor-pointer select-none px-3 h-full rounded-r-lg"
               :class="[ link.active ? activeClass : notActiveClass ]"
               v-else-if="link.label === 'Last'">
            <svg class="w-3 h-3" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M1.95642 0.127357L9.52196 7.6929C9.69177 7.86271 9.69177 8.13729 9.52196 8.3071L1.95642 15.8726C1.78661 16.0425 1.51202 16.0425 1.34222 15.8726L0.626848 15.1573C0.457039 14.9875 0.457039 14.7129 0.626848 14.5431L7.15547 8L0.626848 1.45693C0.457039 1.28712 0.457039 1.01253 0.626848 0.842723L1.34222 0.127357C1.51202 -0.0424522 1.78661 -0.0424522 1.95642 0.127357V0.127357ZM7.12295 0.127357L6.40759 0.842723C6.23778 1.01253 6.23778 1.28712 6.40759 1.45693L12.9362 8L6.40759 14.5431C6.23778 14.7129 6.23778 14.9875 6.40759 15.1573L7.12295 15.8726C7.29276 16.0425 7.56735 16.0425 7.73716 15.8726L15.3027 8.3071C15.4725 8.13729 15.4725 7.86271 15.3027 7.6929L7.73716 0.127357C7.56735 -0.0424522 7.29276 -0.0424522 7.12295 0.127357Z"/></svg>
          </div>

        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: "Pagination",
  props: ['meta'],
  emits: ['changePage'],
  data() {
    return {
      activeClass: 'z-10 bg-blue-50 border-blue-500 text-blue-600',
      notActiveClass: 'z-0 border-gray-300 text-gray-500 hover:bg-gray-50',
    }
  }
}
</script>

