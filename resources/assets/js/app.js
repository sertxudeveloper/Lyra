require('bootstrap');
const $ = require("jquery");

import Popper from 'popper.js';
require('bootstrap-select');

Popper.Defaults.modifiers.computeStyle.gpuAcceleration = !(window.devicePixelRatio < 1.5 && /Win/.test(navigator.platform));

window.toastr = require('toastr');
toastr.options.positionClass = "toast-bottom-right";
// toastr.options.timeOut = 0;
// toastr.success('Have fun storming the castle!', 'Miracle Max Says');

import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import {loadProgressBar} from 'axios-progress-bar'

axios.defaults.baseURL = '/lyra-api';
// axios.defaults.headers.common['X-C/*SRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
// axios.defaults.headers.common['X-Re*/quested-With'] = 'XMLHttpRequest';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

import Dashboard from './components/Dashboard'
import Loader from './components/Loader'

Vue.component('lyra-loader', Loader);

const HTTP_404 = {template: '<div>This is HTTP_404 {{ $route.params }}</div>'};
const HTTP_403 = {template: '<div>This is HTTP_403 {{ $route.params }}</div>'};

import Index from './components/CRUD/Index/Index'
import Show from './components/CRUD/Show/Show'
import Edit from './components/CRUD/Edit/Edit'
import Create from './components/CRUD/Create/Create'

const router = new VueRouter({
  mode: 'history',
  base: 'lyra',
  routes: [

    // Default pages
    {path: '/', name: 'lyra', component: Dashboard},

    // HTTP Errors
    {path: '/404', name: '404', component: HTTP_404},
    {path: '/403', name: '403', component: HTTP_403},

    // Crud Actions
    {path: '/:resourceName', name: 'index', component: Index},
    {path: '/:resourceName/create', name: 'create', component: Create},
    {path: '/:resourceName/:resourceId', name: 'show', component: Show},
    {path: '/:resourceName/:resourceId/edit', name: 'edit', component: Edit},
  ]
});

new Vue({
  el: '#lyra',
  router,
  data: {
    loader: false
  },
  methods: {
    enableLoader: function () {
      this.loader = true
    },
    disableLoader: function () {
      this.loader = false
    }
  },
  created() {
    axios.interceptors.request.use((config) => {
      loadProgressBar(config);
      return config;
    }, (error) => {
      this.disableLoader();
      return Promise.reject(error);
    });

    axios.interceptors.response.use((response) => {
      this.disableLoader();
      return response;
    }, (error) => {
      if (error.response.data.message) toastr.error(error.response.data.message);
      if (error.response.status === 403) {
        // router.push('/403');
        location.reload();
      } else {
        router.push('/404');
      }

      this.disableLoader();
      return Promise.reject(error);
    });
  }
});

$(document).on('click', '.dropdown-menu', function (e) {
  e.stopPropagation();
});

// $(document).on('ready', () => {
//   $('.selectpicker').selectpicker();
// });
