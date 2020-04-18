<li class="nav-item w-100">
  <router-link
    class="nav-link" {{ ($item['key'] !== 'lyra') ?  ":to='$item[prefix]/$item[key]$item[query]'" :  ":to='/' exact" }}>
    @if($item['icon'])
      <div class="icon">
        <i class="{{$item['icon']}} mx-auto"></i>
      </div>
      <span class="d-none d-lg-block">{{$item['name']}}</span>
    @else
      <small class="d-block d-md-none text-white">{{$item['name']}}</small>
      <span class="d-none d-md-block">{{$item['name']}}</span>
    @endif
  </router-link>
</li>
