import Dashboard from "./components/sections/Dashboard";
import MediaManager from "./components/sections/MediaManager";

import Index from "./views/Resources/Index";
import Show from "./views/Resources/Show";
import Create from "./views/Resources/Create";
import Edit from "./views/Resources/Edit";

export default [
  {path: '/', name: 'dashboard', component: Dashboard},
  {path: '/media', name: 'media', component: MediaManager},

  /** Resource routes */
  {
    path: '/resource/:resourceName',
    component: {template: '<router-view></router-view>'},
    children: [
      {path: '', name: 'resource-index', component: Index},
      {path: ':resourceId', name: 'resource-show', component: Show},
      {path: 'create', name: 'resource-create', component: Create},
      {path: ':resourceId/edit', name: 'resource-edit', component: Edit},
    ]
  },
]
