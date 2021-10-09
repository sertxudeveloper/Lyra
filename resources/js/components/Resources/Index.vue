<template>
  <div class="xl:p-4">
    <h1 class="capitalize mb-4 text-3xl text-gray-800">{{ resources?.labels?.plural }}</h1>
    <div v-if="cards.length" class="gap-4 grid grid-cols-1 md:grid-cols-2 mb-4 xl:grid-cols-4">
      <component v-for="card in cards" :is="card.component" :card="card"></component>
    </div>

    <div v-if="resources?.data?.length" class="bg-white flex flex-col max-w-0 min-w-full rounded-lg shadow w-full">
      <div class="flex items-center justify-between h-9 m-4">
        <!-- Left toolbar -->
        <div class="flex items-center gap-x-4 h-full">
          <!-- Selection tool -->
          <div class="flex items-center px-2">
            <input type="checkbox" name="selectAll" v-model="selectAll" class="cursor-pointer h-4 w-4">
            <button class="ml-1 text-gray-600 outline-none focus:ring">
              <svg class="h-5 p-1 w-5" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"></path></svg>
            </button>
          </div>

          <!-- Search tool -->
          <div class="border border-gray-300 flex h-8 items-center rounded text-gray-500">
            <div class="flex-shrink-0 h-4 mx-2 w-4">
              <svg height="16" width="16" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.8633 18.3164L15.1211 13.5742C15.0312 13.4844 14.9141 13.4375 14.7891 13.4375H14.2734C15.5039 12.0117 16.25 10.1562 16.25 8.125C16.25 3.63672 12.6133 0 8.125 0C3.63672 0 0 3.63672 0 8.125C0 12.6133 3.63672 16.25 8.125 16.25C10.1562 16.25 12.0117 15.5039 13.4375 14.2734V14.7891C13.4375 14.9141 13.4883 15.0312 13.5742 15.1211L18.3164 19.8633C18.5 20.0469 18.7969 20.0469 18.9805 19.8633L19.8633 18.9805C20.0469 18.7969 20.0469 18.5 19.8633 18.3164ZM8.125 14.375C4.67188 14.375 1.875 11.5781 1.875 8.125C1.875 4.67188 4.67188 1.875 8.125 1.875C11.5781 1.875 14.375 4.67188 14.375 8.125C14.375 11.5781 11.5781 14.375 8.125 14.375Z"></path></svg>
            </div>
            <div class="h-full w-full pr-1">
              <input type="search" name="search" class="bg-transparent h-full outline-none placeholder-gray-500 text-gray-700 text-sm w-full" placeholder="Search...">
            </div>
          </div>
        </div>

        <!-- Right toolbar -->
        <div class="flex items-center gap-x-4 h-full">
          <!-- Filter tool -->
          <button class="bg-gray-200 h-full flex focus:ring items-center px-3 rounded text-gray-700" @click="getResource">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.3392 0.660806L13.6565 2.34355C12.2089 0.89571 10.2092 0 8 0C3.71703 0 0.220129 3.36574 0.01 7.59655C-0.00093547 7.81648 0.176613 8 0.396806 8H1.30148C1.50642 8 1.6761 7.84026 1.68771 7.63568C1.87616 4.31126 4.62752 1.67742 8 1.67742C9.74719 1.67742 11.3276 2.38461 12.4714 3.52858L10.7254 5.27468C10.4815 5.51855 10.6542 5.93548 10.9991 5.93548H15.6129C15.8267 5.93548 16 5.76216 16 5.54839V0.934548C16 0.589677 15.583 0.416968 15.3392 0.660806V0.660806ZM15.6032 8H14.6985C14.4936 8 14.3239 8.15974 14.3123 8.36432C14.1238 11.6887 11.3725 14.3226 8 14.3226C6.25281 14.3226 4.67235 13.6154 3.52858 12.4714L5.27465 10.7253C5.51852 10.4815 5.34581 10.0645 5.00094 10.0645H0.387097C0.173323 10.0645 0 10.2378 0 10.4516V15.0655C0 15.4103 0.416968 15.583 0.660806 15.3392L2.34355 13.6565C3.79113 15.1043 5.79084 16 8 16C12.283 16 15.7799 12.6343 15.99 8.40345C16.0009 8.18352 15.8234 8 15.6032 8Z"/></svg>
          </button>

          <!-- Filter tool -->
          <button class="bg-gray-200 cursor-pointer flex focus:ring gap-1.5 gap-x-2 h-full items-center px-3 rounded text-gray-700 outline-none">
            <svg width="19" height="19" viewBox="0 0 22 22" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M20.6236 0H1.37663C0.155245 0 -0.461013 1.48186 0.404334 2.34725L8.25 10.1946V18.2187C8.24999 18.4147 8.29188 18.6085 8.37287 18.7869C8.45385 18.9654 8.57206 19.1245 8.71956 19.2535L11.4696 21.659C12.3478 22.4275 13.75 21.8172 13.75 20.6243V10.1946L21.5959 2.34725C22.4595 1.48362 21.8475 0 20.6236 0ZM12.375 9.625V20.625L9.625 18.2187V9.625L1.375 1.375H20.625L12.375 9.625Z"/></svg>
            <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"/></svg>
          </button>

          <!-- New resource button -->
          <router-link class="bg-blue-600 flex focus:ring h-full items-center px-3 rounded outline-none"
                       :to="{ name: 'resource-create', params: { resourceName: $route.params.resourceName } }">
            <span class="text-base text-white">New {{ resources.labels.singular }}</span>
          </router-link>
        </div>
      </div>

      <!-- Resource Table-->
      <div class="overflow-x-auto">
        <table class="w-full border-b border-gray-200">
          <thead class="bg-gray-100">
          <tr class="uppercase text-left text-xs tracking-wider text-gray-500">
            <th scope="col" class="px-6 py-4">&nbsp;</th>
            <th scope="col" v-for="field in resources.data[0]?.fields"
                class="px-6 py-4 font-medium">{{ field.name }}</th>
            <th scope="col" class="px-6 py-4">&nbsp;</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-gray-600">
          <tr v-for="resource in resources.data" :key="resource.key">
            <td class="px-6 py-4 whitespace-nowrap">
              <input type="checkbox" name="select" v-model="selected" :value="resource.key" class="cursor-pointer h-4 w-4">
            </td>
            <td v-for="field in resource.fields" class="px-6 py-4 whitespace-nowrap">
              <component :is="`index-${field.component}`" :field="field" />
            </td>
            <td class="flex px-2 py-2.5 text-gray-400 whitespace-nowrap">
              <router-link :to="{ name: 'resource-show', params: { resourceName: $route.params.resourceName, resourceId: resource.key } }" class="h-9 hover:text-blue-500 p-2 w-9">
                <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M8 5.22223C7.70559 5.22683 7.41336 5.2736 7.13222 5.36112C7.26227 5.58982 7.33151 5.84804 7.33333 6.11112C7.33333 6.3154 7.2931 6.51767 7.21492 6.7064C7.13675 6.89513 7.02217 7.06662 6.87772 7.21106C6.73327 7.35551 6.56179 7.47009 6.37306 7.54827C6.18433 7.62644 5.98205 7.66668 5.77777 7.66668C5.51469 7.66485 5.25647 7.59561 5.02777 7.46556C4.84734 8.09134 4.86837 8.75803 5.08788 9.37119C5.3074 9.98435 5.71428 10.5129 6.25088 10.882C6.78748 11.251 7.4266 11.4419 8.07771 11.4276C8.72883 11.4132 9.35892 11.1944 9.87875 10.802C10.3986 10.4097 10.7818 9.86374 10.9741 9.24151C11.1664 8.61927 11.158 7.95231 10.9502 7.33508C10.7424 6.71786 10.3456 6.18167 9.81613 5.80246C9.28665 5.42326 8.65127 5.22026 8 5.22223V5.22223ZM15.9033 7.92779C14.397 4.98861 11.4147 3 8 3C4.58527 3 1.60221 4.99 0.0966517 7.92806C0.0331076 8.05376 0 8.19264 0 8.33348C0 8.47433 0.0331076 8.6132 0.0966517 8.7389C1.60304 11.6781 4.58527 13.6667 8 13.6667C11.4147 13.6667 14.3978 11.6767 15.9033 8.73862C15.9669 8.61292 16 8.47405 16 8.3332C16 8.19236 15.9669 8.05348 15.9033 7.92779V7.92779ZM8 12.3334C5.25972 12.3334 2.74749 10.8056 1.39082 8.33334C2.74749 5.86112 5.25944 4.33334 8 4.33334C10.7406 4.33334 13.2525 5.86112 14.6092 8.33334C13.2528 10.8056 10.7406 12.3334 8 12.3334Z"/></svg>
              </router-link>
              <router-link :to="{ name: 'resource-edit', params: { resourceName: $route.params.resourceName, resourceId: resource.key } }" class="h-9 hover:text-blue-500 p-2.5 w-9">
                <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.3628 2.30078L13.6796 0.618308C12.8553 -0.206035 11.521 -0.20616 10.6965 0.618277L0.778433 10.4715L0.0102775 15.1276C-0.0733162 15.6344 0.365527 16.0733 0.872371 15.9897L5.52846 15.2215L15.3824 5.30349C16.2052 4.48065 16.2131 3.15102 15.3628 2.30078V2.30078ZM3.77012 9.43749L9.09071 4.1514L11.8486 6.90924L6.56249 12.2298V10.9375H5.06249V9.43749H3.77012ZM2.56662 14.3166L1.6834 13.4334L2.06278 11.1338L2.63778 10.5625H3.93749V12.0625H5.43749V13.3622L4.86618 13.9372L2.56662 14.3166V14.3166ZM14.4099 4.33121L14.4083 4.3328L14.4067 4.3344L12.9058 5.84515L10.1548 3.09421L11.6656 1.59328L11.6671 1.59171L11.6687 1.59015C11.9546 1.30434 12.418 1.30081 12.7073 1.59012L14.3903 3.27306C14.699 3.58171 14.7009 4.04021 14.4099 4.33121V4.33121Z"/></svg>
              </router-link>
              <button @click="remove(resource.key)" class="h-9 hover:text-red-500 p-2.5 w-9">
                <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9.375 13H10.125C10.2245 13 10.3198 12.9605 10.3902 12.8902C10.4605 12.8198 10.5 12.7245 10.5 12.625V5.875C10.5 5.77554 10.4605 5.68016 10.3902 5.60984C10.3198 5.53951 10.2245 5.5 10.125 5.5H9.375C9.27554 5.5 9.18016 5.53951 9.10983 5.60984C9.03951 5.68016 9 5.77554 9 5.875V12.625C9 12.7245 9.03951 12.8198 9.10983 12.8902C9.18016 12.9605 9.27554 13 9.375 13ZM14.5 2.5H11.9247L10.8622 0.728125C10.7288 0.505942 10.5402 0.322091 10.3147 0.194487C10.0892 0.066882 9.83444 -0.000123231 9.57531 1.70139e-07H6.42469C6.16567 -1.5274e-05 5.91106 0.0670412 5.68566 0.194641C5.46025 0.32224 5.27172 0.506033 5.13844 0.728125L4.07531 2.5H1.5C1.36739 2.5 1.24021 2.55268 1.14645 2.64645C1.05268 2.74022 1 2.86739 1 3V3.5C1 3.63261 1.05268 3.75979 1.14645 3.85355C1.24021 3.94732 1.36739 4 1.5 4H2V14.5C2 14.8978 2.15804 15.2794 2.43934 15.5607C2.72064 15.842 3.10218 16 3.5 16H12.5C12.8978 16 13.2794 15.842 13.5607 15.5607C13.842 15.2794 14 14.8978 14 14.5V4H14.5C14.6326 4 14.7598 3.94732 14.8536 3.85355C14.9473 3.75979 15 3.63261 15 3.5V3C15 2.86739 14.9473 2.74022 14.8536 2.64645C14.7598 2.55268 14.6326 2.5 14.5 2.5ZM6.37 1.59094C6.38671 1.56312 6.41035 1.54012 6.43862 1.52418C6.46688 1.50824 6.4988 1.49991 6.53125 1.5H9.46875C9.50115 1.49996 9.533 1.50832 9.5612 1.52426C9.58941 1.54019 9.613 1.56317 9.62969 1.59094L10.1753 2.5H5.82469L6.37 1.59094ZM12.5 14.5H3.5V4H12.5V14.5ZM5.875 13H6.625C6.72446 13 6.81984 12.9605 6.89016 12.8902C6.96049 12.8198 7 12.7245 7 12.625V5.875C7 5.77554 6.96049 5.68016 6.89016 5.60984C6.81984 5.53951 6.72446 5.5 6.625 5.5H5.875C5.77554 5.5 5.68016 5.53951 5.60984 5.60984C5.53951 5.68016 5.5 5.77554 5.5 5.875V12.625C5.5 12.7245 5.53951 12.8198 5.60984 12.8902C5.68016 12.9605 5.77554 13 5.875 13Z"/></svg>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>

      <div class="flex justify-between p-2 items-center">
        <div></div>
        <div class="flex items-center gap-x-4">
          <pagination :meta="resources.meta" @changePage="changePage" />
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: "Index",
  data() {
    return {
      resources: {},
      cards: [],
      selected: [],
      page: null,
      perPage: null
    }
  },
  mounted() {
    this.getResource()
    this.getCards()
  },
  methods: {
    getResource() {
      this.$http.get(`/resource/${this.$route.params.resourceName}?perPage=${this.perPage ?? ''}&page=${this.page ?? ''}`)
          .then(response => {
            this.resources = response.data
            this.page = this.resources.meta.current_page
            this.perPage = this.resources.meta.per_page
          })
    },
    getCards() {
      this.$http.get(`/cards/${this.$route.params.resourceName}`)
          .then(response => this.cards = response.data)
    },
    changePage(page) {
      this.page = page
      this.getResource()
    },
    remove(index) {
      console.log('deleteResource', index)
    }
  },
  computed: {
    selectAll: {
      get() {
        return this.resources.data.length ? this.selected.length === this.resources.data.length : false;
      },
      set(value) {
        let selected = [];

        if (value) {
          this.resources.data.forEach(resource => selected.push(resource.key));
        }

        this.selected = selected;
      }
    }
  }
}
</script>

<style scoped>

</style>
