import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'

import registerCards from './cards'

import Dashboard from "@/components/sections/Dashboard";
import MediaManager from "@/components/sections/MediaManager";
import ResourceIndex from "@/components/sections/ResourceIndex";
import ResourceShow from "@/components/sections/ResourceShow";

const routes = [
  { path: '/', name: 'dashboard', component: Dashboard },
  { path: '/media', name: 'media', component: MediaManager },

  { path: '/resource/:resourceName', name: 'resourceIndex', component: ResourceIndex },
  { path: '/resource/:resourceName/:resourceId', name: 'resourceShow', component: ResourceShow },
]

axios.defaults.baseURL = window.config.apiRoute

const router = createRouter({
  history: createWebHistory(window.config.base),
  routes,
})

const app = createApp({})

registerCards(app)

app.use(router)
app.use(VueAxios, axios)
app.mount('#app')
