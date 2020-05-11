<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="lyra-page theme-{{ lyra_theme() }}">
<head>
  <title>@yield('page_title', trans('lyra::theme.title') . " - " . trans('lyra::theme.description'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <meta name="lyra-api-route" content="{{ config('lyra.routes.api.prefix') }}">
  <meta name="lyra-web-route" content="{{ config('lyra.routes.web.prefix') }}">
  <link rel="icon" type="image/png" href="data:image/png;base64,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" sizes="32x32">

  @foreach(\SertxuDeveloper\Lyra\Lyra::allStyles() as $name => $style)
    <link rel="stylesheet" href="/{{ config('lyra.routes.api.prefix') }}/styles/{{$name}}">
  @endforeach

</head>

<body>
<div id="lyra">
  <nav class="navbar fixed-top flex-md-nowrap p-0">

    <a class="col-3 col-lg-2 d-none d-lg-block mr-0 navbar-brand" href="{{ lyra_route('dashboard') }}">
      <img src="/{{ config('lyra.routes.api.prefix') }}/assets/lyra-logo" alt="Logo Lyra" class="h-100">
    </a>

    <div style="flex: 0 0 60px;overflow: hidden;" class="p-2 d-flex d-lg-none">
      <img src="/{{ config('lyra.routes.api.prefix') }}/assets/lyra-icon" alt="Logo Lyra" class="h-100">
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
      <nav class="col-1 col-lg-2 d-block sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            @foreach(Lyra::getMenuItems() as $item)
              @include('lyra::common.sidebar.item')
            @endforeach
          </ul>
        </div>
      </nav>

      <main role="main" class="col-11 col-lg-10 ml-sm-auto px-0">

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

@foreach(\SertxuDeveloper\Lyra\Lyra::allScripts() as $name => $script)
  <script src="/{{ config('lyra.routes.api.prefix') }}/scripts/{{ $name }}"></script>
@endforeach

<script>Lyra.ready();</script>

</body>
</html>
