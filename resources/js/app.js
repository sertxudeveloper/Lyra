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

import NotificationWrapper from "./components/Notifications/Wrapper";

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

app.component('notification-wrapper', NotificationWrapper)

app.config.globalProperties.$notify = (options) => {
  app._instance.refs['notification-wrapper'].add(options)
}

app.use(router)
app.use(VueAxios, axios)
app.mount('#app')

axios.interceptors.response.use((response) => {
  return response;
}, (error) => {
  // if (error.response.status === 403) router.push('/403');
  // if (error.response.status === 404) router.push('/404');

  if (error.response.status === 500) {
    app.config.globalProperties.$notify({ type: 'error', title: 'Error', text: `An internal error occurred.\n${error.response.data.message}`, timeout: 8000 })
  }

  return Promise.reject(error);
});
