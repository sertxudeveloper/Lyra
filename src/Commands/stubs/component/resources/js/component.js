import Component from './components/Component'

Lyra.register((Vue, router) => {
  router.addRoutes([
    {name: '{{ component }}', path: '/components/{{ component }}', component: Component}
  ]);

});
