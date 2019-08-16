<li class="nav-item">
  <router-link
    class="nav-link" {{ ($item['key'] !== 'lyra') ?  ":to='/$item[key]'" :  ":to='/' exact" }}>
    <div class="icon">
      <i class="{{$item['icon']}}"></i>
    </div>
    <span class="d-none d-sm-block">{{$item['name']}}</span>
  </router-link>
</li>
