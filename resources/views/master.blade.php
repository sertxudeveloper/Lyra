<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>@yield('page_title', config('lyra.admin.title') . " - " . config('lyra.admin.description'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <meta name="lyra-api-route" content="{{ config('lyra.routes.api.prefix') }}">

  <link rel="stylesheet" href="{{ lyra_asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ Lyra::getPreferredTheme() }}">
</head>

<body>
<div id="lyra">
  <nav class="navbar fixed-top flex-md-nowrap p-0">
    <a class="col-lg-2 col-md-3 col-sm-3 d-none d-sm-block mr-0 navbar-brand" href="{{ lyra_route('dashboard') }}">
      <img src="{{ lyra_asset('images/lyra-logo.png') }}" alt="Logo Lyra" class="h-100">
    </a>

    <div style="flex: 0 0 60px;overflow: hidden;" class="p-2 d-flex d-sm-none">
      <img src="http://lyrawebsite.local/vendor/sertxudeveloper/lyra/assets/images/lyra-logo.png"
           alt="Logo Lyra" class="h-100">
    </div>

    <div class="col d-flex px-0 px-md-3 shadow w-100 navbar-top">

      <global-search></global-search>

      <div class="align-items-center d-flex h-100 right-menu">
        <div class="d-flex h-100 menu-icons px-0">
          @if(config('lyra.notificator'))
            <notifications></notifications>
          @endif
        </div>

        <div class="mx-3 divider"></div>
        @include('lyra::common.navbar.user')
      </div>

    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav class="col-1 col-sm-3 col-md-3 col-lg-2 d-block sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            @foreach(Lyra::getMenuItems() as $item)
              @include('lyra::common.sidebar.item')
            @endforeach
          </ul>
        </div>
      </nav>

      <main role="main" class="col-11 col-lg-10 col-md-9 col-sm-9 ml-sm-auto px-0">

        <div class="container-fluid p-0 m-0" :class="{ loading: loader }">
          @yield('content')
        </div>

        <footer class="px-3">
          <div>
          <span>{!! trans('lyra::theme.footer_copyright', ['year' => date("Y")]) !!}
            - {{ trans('lyra::theme.version') }} {{ Lyra::getVersion() }}</span>
          </div>
        </footer>
      </main>

    </div>
  </div>
</div>

<script src="{{ lyra_asset('js/app.js') }}"></script>

</body>
</html>
