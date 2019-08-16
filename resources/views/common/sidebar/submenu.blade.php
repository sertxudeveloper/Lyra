<li class="nav-item">
  <a href="#{{$item['key']}}Submenu" data-toggle="collapse" aria-expanded="false"
     class="dropdown-toggle collapsed nav-link">
    <div class="icon">
      <i class="{{$item['icon']}}"></i>
    </div>
    <span class="d-none d-sm-block">{{$item['name']}}</span>
  </a>
  <ul class="list-unstyled collapse ml-3" id="{{$item['key']}}Submenu">
    @foreach($item['items'] as $item)
      {{--<li class="nav-item">
        <router-link
          class="nav-link" :to="'/{{$item['key']}}'" exact>
          <div class="icon">
            <i class="{{$item['icon']}}"></i>
          </div>
          <span class="d-none d-sm-block">{{$item['name']}}</span>
        </router-link>
      </li>--}}
      @include('lyra::common.sidebar.item')
    @endforeach
  </ul>
</li>
