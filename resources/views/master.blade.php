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
          <div class="align-items-center d-flex h-100 justify-content-center px-2 dropdown">
            <a href="#" role="button" id="helpDropdown" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false">
              <i class="fas fa-life-ring"></i>
            </a>

            <div class="dropdown-large dropdown-menu dropdown-menu-right" aria-labelledby="helpDropdown">
              <p class="text-center my-5">Help not available.</p>
            </div>
          </div>
          <div class="align-items-center d-flex h-100 justify-content-center px-2 dropdown">
            <a href="#" role="button" id="notifyDropdown" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false">
              <i class="fas fa-bell"></i>
              <span class="badge badge-danger">{{ count(auth()->user()->unreadNotifications) }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-large" aria-labelledby="notifyDropdown">
              <div class="list-group">

                @foreach(auth()->user()->notifications as $notification)
                  <a
                    class="border-left-0 border-right-0 list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                      <h6 class="font-weight-bold mb-1">{{ $notification->data['title'] }}</h6>
                      <small>{{ $notification->created_at }}</small>
                    </div>
                    <span class="mb-1" style="font-size: 0.85rem;">{{ $notification->data['message'] }}</span>
                  </a>
                @endforeach

              </div>

            </div>
          </div>
        </div>

        <div class="mx-3 divider"></div>
        <div class="dropdown h-100">
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
            <a class="dropdown-item" href="{{ lyra_route('logout') }}">Cerrar sesión</a>
          </div>
        </div>
      </div>

    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav class="col-1 col-sm-3 col-md-3 col-lg-2 d-block sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">

            @foreach(Lyra::getMenuItems() as $item)
              <li class="nav-item">
                <router-link class="nav-link" {{ ($item['key'] !== 'lyra') ?  ":to='$item[key]'" :  ":to='/'" }}>
                  <div class="icon">
                    <i class="{{$item['icon']}}"></i>
                  </div>
                  <span class="d-none d-sm-block">{{$item['name']}}</span>
                </router-link>
              </li>
            @endforeach

          </ul>

        </div>
      </nav>

      <main role="main" class="col-11 col-lg-10 col-md-9 col-sm-9 ml-sm-auto px-0">

        <div class="container-fluid px-3">
          @yield('content')
        </div>

        <footer class="px-3">
          <div>
          <span>{!! trans('lyra::theme.footer_copyright') !!}
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
