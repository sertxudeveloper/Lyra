@if(!isset($item['items']))
  @include('lyra::common.sidebar.link')
@else
  @include('lyra::common.sidebar.submenu')
@endif
