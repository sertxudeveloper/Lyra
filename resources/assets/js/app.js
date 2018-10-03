
require('bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
// import axios from 'axios'
// import VueAxios from 'vue-axios'

Vue.use(VueRouter);

import Dashboard from './components/Dashboard'

const Profile = { template: '<div>This is Profile {{ $route.params.id }}</div>' };
const Media = { template: '<div>This is Media {{ $route.params.id }}</div>' };
const Roles = { template: '<div>This is Roles {{ $route.params.id }}</div>' };
const Menu = { template: '<div>This is Menu {{ $route.params.id }}</div>' };
const Settings = { template: '<div>This is Settings {{ $route.params.id }}</div>' };
const Crud = { template: '<div>This is Crud {{ $route.params.id }}</div>' };

const HTTP_404 = { template: '<div>This is HTTP_404 {{ $route.params }}</div>' };
const HTTP_403 = { template: '<div>This is HTTP_403 {{ $route.params }}</div>' };

const Index = { template: '<div>This is Resource Index {{ $route.params }}</div>' };
const Create = { template: '<div>This is Resource Create {{ $route.params }}</div>' };
const Show = { template: '<div>This is Resource Show {{ $route.params }}</div>' };
const Edit = { template: '<div>This is Resource Edit {{ $route.params }}</div>' };

const router = new VueRouter({
  mode: 'history',
  base: 'lyra',
  routes: [

    // Default pages
    { path: '/', name: 'lyra', component: Dashboard },
    // { path: '/profile', name: 'profile', component: Profile },
    { path: '/media', name: 'media', component: Media },
    // { path: '/roles', name: 'roles', component: Roles },
    { path: '/menu', name: 'menu', component: Menu },
    { path: '/settings', name: 'settings', component: Settings },
    { path: '/crud', name: 'crud', component: Crud },

    // HTTP Errors
    { path: '/404', name: '404', component: HTTP_404 },
    { path: '/403', name: '403', component: HTTP_403 },

    // Crud Actions
    { path: '/:resourceName', name: 'index', component: Index },
    { path: '/:resourceName/create', name: 'create', component: Create },
    { path: '/:resourceName/:resourceId', name: 'show', component: Show },
    { path: '/:resourceName/:resourceId/edit', name: 'edit', component: Edit },
  ]
});

new Vue({
  el: '#lyra',
  router
});