import {createApp} from 'vue'
import {createRouter, createWebHistory} from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'

import registerCards from './cards'
import registerFields from './fields'
import registerComponents from './components'

import routes from "./routes";

import VueClickAway from "vue3-click-away";

axios.defaults.baseURL = window.config.apiRoute

const router = createRouter({
  history: createWebHistory(window.config.base), routes,
})

const app = createApp({})

/** Register Lyra elements */
registerCards(app)
registerFields(app)
registerComponents(app)

/** Register plugins */
app.use(router)
app.use(VueAxios, axios)
app.use(VueClickAway)

app.mount('#app')

/** Axios error interceptor */
axios.interceptors.response.use(response => response, error => {

  if (error.response.status === 500) {
    app.config.globalProperties.$notify({
      type: 'error', title: 'Error', text: `An internal error occurred.\n${error.response.data.message}`, timeout: 8000
    })
  }

  return Promise.reject(error)
});
