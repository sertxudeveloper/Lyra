import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'

import registerCards from './cards'
import registerFields from './fields'

import Dashboard from "./components/sections/Dashboard";
import MediaManager from "./components/sections/MediaManager";

import ResourcesIndex from "./components/Resources/Index";
import ResourcesShow from "./components/Resources/Show";
import ResourcesCreate from "./components/Resources/Create";
import ResourcesEdit from "./components/Resources/Edit";

import Pagination from "./components/elements/Pagination";

const routes = [
  { path: '/', name: 'dashboard', component: Dashboard },
  { path: '/media', name: 'media', component: MediaManager },

  { path: '/resource/:resourceName', name: 'resourceIndex', component: ResourcesIndex },
  { path: '/resource/:resourceName/:resourceId', name: 'resourceShow', component: ResourcesShow },
  { path: '/resource/:resourceName/create', name: 'resourceCreate', component: ResourcesCreate },
  { path: '/resource/:resourceName/:resourceId/edit', name: 'resourceShow', component: ResourcesEdit },
]

axios.defaults.baseURL = window.config.apiRoute

const router = createRouter({
  history: createWebHistory(window.config.base),
  routes,
})

const app = createApp({})

registerCards(app)
registerFields(app)

app.component('pagination', Pagination)

app.use(router)
app.use(VueAxios, axios)
app.mount('#app')
