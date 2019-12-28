require('bootstrap');
const $ = require("jquery");
window.$ = $;
window.jQuery = $;

import Popper from 'popper.js';

require('bootstrap-select');

Popper.Defaults.modifiers.computeStyle.gpuAcceleration = !(window.devicePixelRatio < 1.5 && /Win/.test(navigator.platform));

window.toastr = require('toastr');
toastr.options.positionClass = "toast-bottom-right";

import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import {loadProgressBar} from 'axios-progress-bar'

axios.defaults.baseURL = '/lyra-api';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

import Dashboard from './components/Dashboard'
import MediaManager from './components/MediaManager'
import Profile from './components/Profile'
import Loader from './components/Loader'
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'

/**
 * Vue.js register dynamically the components
 * Two modalities available: "editable" and "readable"
 */
const requireComponentEditable = require.context('./components/Fields/Edit', false, /[A-Z]\w+\.(vue|)$/);
requireComponentEditable.keys().forEach(fileName => {
  const componentConfig = requireComponentEditable(fileName);
  const componentName = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

  Vue.component(`${componentName}Editable`, componentConfig.default || componentConfig)
});

const requireComponentReadable = require.context('./components/Fields/Read', false, /[A-Z]\w+\.(vue|)$/);
requireComponentReadable.keys().forEach(fileName => {
  const componentConfig = requireComponentReadable(fileName);
  const componentName = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

  Vue.component(`${componentName}Readable`, componentConfig.default || componentConfig)
});

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
    {path: '/media', name: 'media', component: MediaManager},
    {path: '/profile', name: 'profile', component: Profile},

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

import GlobalSearch from './components/GlobalSearch';
import Notifications from './components/Notifications';

new Vue({
  el: '#lyra',
  router,
  components: {GlobalSearch, Notifications},
  data: {
    loader: false
  },
  methods: {
    enableLoader: function () {
      this.loader = true;
    },
    disableLoader: function () {
      this.loader = false;
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
      if (error.response.status === 400) return Promise.reject(error.response);
      if (error.response.data.message) toastr.error(error.response.data.message);
      if (error.response.status === 403) {
        router.push('/403');
      } else if (error.response.status === 404) {
        router.push('/404');
      }

      this.disableLoader();
      return Promise.reject(error);
    });
  },
  mounted() {
    $('.router-link-active').parents('ul.list-unstyled').addClass('show');
  }
});

$(document).on('click', '.dropdown-menu', function (e) {
  e.stopPropagation();
});

// $(document).on('ready', () => {
//   $('.selectpicker').selectpicker();
// });
