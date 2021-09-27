import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from "./components/App.vue"

const routes = [
  { path: '/', component: Home },
  { path: '/about', component: About },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)

app.use(router)
app.mount('#app')
