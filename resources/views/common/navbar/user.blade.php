<div class="dropdown h-100">
  <a id="navbarMenuUser" class="user" data-toggle="dropdown" aria-haspopup="true" role="button"
     aria-expanded="false">
    <div class="align-items-center d-flex h-100 px-0 mr-2">
      <div class="align-items-center d-flex h-100">
        <span class="d-none d-lg-block">{{ Lyra::auth()->user()->{config('lyra.user.name')} }}</span>
      </div>
      <div class="align-items-center avatar d-flex h-100 pl-0 pl-lg-3 pr-3">
        {{--                <img src="{{ asset("storage/" . lyra_auth()->user()->avatar) }}" alt="{{ lyra_auth()->user()->name }}">--}}
        @if(!config('lyra.avatar'))
          <img src="//gravatar.com/avatar/{{md5(Lyra::auth()->user()->{config('lyra.user.email')})}}?d=mp" alt="{{ Lyra::auth()->user()->{config('lyra.user.name')} }}">
        @else
          <img src="{{ asset("storage/" . Lyra::auth()->user()->{config('lyra.avatar')}) }}" alt="{{ Lyra::auth()->user()->{config('lyra.user.name')} }}">
        @endif
      </div>
      <i class="fa-chevron-down fas"></i>
    </div>
  </a>
  <div class="dropdown-menu dropdown-menu-right mr-2" aria-labelledby="navbarMenuUser">
    <a class="dropdown-item" href="{{ lyra_route('logout') }}">Cerrar sesión</a>
  </div>
</div>
