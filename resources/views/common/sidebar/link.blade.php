<li class="nav-item w-100">
  <router-link
    class="nav-link" {{ ($item['key'] !== 'lyra') ?  ":to='/$item[key]'" :  ":to='/' exact" }}>
    @if($item['icon'])
      <div class="icon">
        <i class="{{$item['icon']}} mx-auto"></i>
      </div>
      <span class="d-none d-lg-block">{{$item['name']}}</span>
    @else
      <small class="text-white">{{$item['name']}}</small>
    @endif
  </router-link>
</li>
