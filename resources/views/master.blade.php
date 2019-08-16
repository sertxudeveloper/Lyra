<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>@yield('page_title', config('lyra.admin.title') . " - " . config('lyra.admin.description'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>

  <link rel="stylesheet" href="{{ lyra_asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ Lyra::getPreferredTheme() }}">
</head>

<body>
<div id="lyra">
  {{--  {{ dd(auth()->user()->notify(new \App\Notifications\LyraVersion())) }}--}}
  {{--{{ dd(auth()->user()->notifications) }}--}}
  <nav class="navbar fixed-top flex-md-nowrap p-0">
    {{--<a class="navbar-brand col-sm-3 col-md-3 col-lg-2 mr-0" href="{{ lyra_route('dashboard') }}">--}}
    <a class="col-lg-2 col-md-3 col-sm-3 d-none d-sm-block mr-0 navbar-brand" href="{{ lyra_route('dashboard') }}">
      <img src="{{ lyra_asset('images/lyra-logo.png') }}" alt="Logo Lyra" class="h-100">
    </a>
    {{--</a>--}}

    <div style="flex: 0 0 60px;overflow: hidden;" class="p-2 d-flex d-sm-none">
      <img src="http://lyrawebsite.local/vendor/sertxudeveloper/lyra/assets/images/lyra-logo.png"
           alt="Logo Lyra" class="h-100">
    </div>

    <div class="col d-flex px-0 px-md-3 shadow w-100 navbar-top">

      <form class="col form-inline h-100 pl-0 pl-sm-3 pr-0">
        <div class="align-items-center d-flex form-group h-100 m-0 w-100">
          <i class="d-none d-sm-block fa-search fas"></i>
          <input type="text" id="search" class="form-control h-100 m-0" name="search" autofocus
                 placeholder="Search models, actions or help">

        </div>
      </form>

      <div class="align-items-center d-flex h-100 right-menu">
        <div class="d-flex h-100 menu-icons px-0">
          @include('lyra::common.navbar.help')
          @if(config('lyra.notificator'))
            @include('lyra::common.navbar.notifications')
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

            {{--@php dd(Lyra::getMenuItems()); @endphp--}}

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
