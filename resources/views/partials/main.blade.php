<!-- Main --->
<div class="flex flex-col h-full w-full">
  @include('lyra::partials.navbar')

  <!-- Content --->
  <div class="h-full overflow-y-auto p-4 w-full">
    <router-view :key="$route.path"></router-view>
  </div>
</div>
