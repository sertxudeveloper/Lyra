import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'

import registerCards from './cards'
import registerFields from './fields'

import Dashboard from "./components/sections/Dashboard";
import MediaManager from "./components/sections/MediaManager";

import Index from "./components/Resources/Index";
import Show from "./components/Resources/Show";
import Create from "./components/Resources/Create";
import Edit from "./components/Resources/Edit";

import Pagination from "./components/elements/Pagination";

const routes = [
  { path: '/', name: 'dashboard', component: Dashboard },
  { path: '/media', name: 'media', component: MediaManager },

  { path: '/resource/:resourceName', name: 'resource-index', component: Index },
  { path: '/resource/:resourceName/:resourceId', name: 'resource-show', component: Show },
  { path: '/resource/:resourceName/create', name: 'resource-create', component: Create },
  { path: '/resource/:resourceName/:resourceId/edit', name: 'resource-edit', component: Edit },
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
