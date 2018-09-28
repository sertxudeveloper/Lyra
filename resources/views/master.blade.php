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
<nav class="navbar fixed-top flex-md-nowrap p-0">
  <a class="navbar-brand col-sm-3 col-md-3 col-lg-2 mr-0" href="{{ lyra_route('dashboard') }}">
    <img src="{{ lyra_asset('images/lyra-logo.png') }}" alt="Logo Lyra" class="h-100">
  </a>
  <div class="shadow w-100 d-flex col">

    <form class="col form-inline h-100">
      <div class="form-group h-100 w-100">
        <i class="fas fa-search"></i>
        <input type="text" id="search" class="form-control h-100" name="search" autofocus=""
               placeholder="Search models, actions or help">
      </div>
    </form>

    <div class="align-items-center d-flex h-100 right-menu">
      <div class="d-flex h-100 menu-icons px-0">
        <div class="align-items-center d-flex h-100 justify-content-center px-2">
          <a href="#">
            <i class="fas fa-life-ring"></i>
          </a>
        </div>
        <div class="align-items-center d-flex h-100 justify-content-center px-2">
          <a href="#">
            <i class="fas fa-comments"></i>
          </a>
        </div>
        <div class="align-items-center d-flex h-100 justify-content-center px-2">
          <a href="#">
            <i class="fas fa-bell"></i>
          </a>
        </div>
      </div>

      <div class="mx-3 divider"></div>
      <a id="navbarMenuUser" class="user" data-toggle="dropdown" aria-haspopup="true" role="button"
         aria-expanded="false">
        <div class="align-items-center d-flex h-100 px-0 mr-2">
          <div class="align-items-center d-flex h-100">
            <span class="d-none d-lg-block">{{ auth()->user()->name }}</span>
          </div>
          <div class="align-items-center avatar d-flex h-100 pl-0 pl-lg-3 pr-3">
            <img src="{{ asset("storage/" . auth()->user()->avatar) }}"
                 alt="John Alexander Doe Avatar">
          </div>
          <i class="fa-chevron-down fas"></i>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right mr-2" aria-labelledby="navbarMenuUser">
        <a class="dropdown-item" href="{{ lyra_route('profile') }}">Perfil</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ lyra_route('logout') }}">Cerrar sesión</a>
      </div>
    </div>

  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-1 col-sm-3 col-md-3 col-lg-2 d-block sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">

          {{--          @php dd(Lyra::getMenuItems()) @endphp--}}

          {{--@foreach(Lyra::getMenuItems() as $item)
            <li class="nav-item">
              <a class="nav-link{{ request()->fullUrlIs($item->) ? ' active' : '' }}"
                 href="{{$item->link(true)}}">
                <div class="icon">
                  <i class="fas fa-home"></i>
                  <i class="{{$item->icon}}"></i>
                </div>
                <span class="d-none d-sm-block">{{$item->name}}</span>
              </a>
            </li>
          @endforeach--}}

          @foreach(Lyra::getMenuItems() as  $key => $item)

            {{--@php dd($item) @endphp--}}

            <li class="nav-item">
              <a class="nav-link {{ request()->fullUrlIs($item['link']) ? ' active' : '' }}"
                 href="{{ $item['link'] }}">
                <div class="icon">
                  {{--<i class="fas fa-home"></i>--}}
                  <i class="{{$item['icon']}}"></i>
                </div>
                <span class="d-none d-sm-block">{{$item['name']}}</span>
              </a>
            </li>
          @endforeach

        </ul>

      </div>
    </nav>

    <main role="main" class="col-11 col-sm-9 col-md-9 col-lg-10 ml-sm-auto px-4">

      <div class="container-fuid">
        @yield('content')
      </div>

      <footer>
        <div>
          <span>{!! trans('lyra::theme.footer_copyright') !!}
            - {{ trans('lyra::theme.version') }} {{ Lyra::getVersion() }}</span>
        </div>
      </footer>
    </main>

  </div>
</div>

<script src="{{ lyra_asset('js/app.js') }}"></script>

</body>
</html>
