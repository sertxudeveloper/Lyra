<div class="align-items-center d-flex h-100 justify-content-center px-2 dropdown">
  <a href="#" role="button" id="notifyDropdown" data-toggle="dropdown" aria-haspopup="true"
     aria-expanded="false">
    <i class="fas fa-bell"></i>
    @if(Arr::first(Lyra::auth()->user()->unreadNotifications))
      <span class="badge badge-danger">
                  {{ count(Lyra::auth()->user()->unreadNotifications) }}
                </span>
    @endif
  </a>

  <div class="dropdown-menu dropdown-menu-right dropdown-large" aria-labelledby="notifyDropdown">
    <div class="list-group">
      @if(Arr::first(Lyra::auth()->user()->notifications))
        @foreach(Lyra::auth()->user()->notifications as $notification)
          <a
            class="border-left-0 border-right-0 list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="font-weight-bold mb-1">{{ $notification->data['title'] }}</h6>
              <small>{{ $notification->created_at }}</small>
            </div>
            <span class="mb-1" style="font-size: 0.85rem;">{{ $notification->data['message'] }}</span>
          </a>
        @endforeach
      @else
        <span class="py-5 text-center text-muted">You haven't got any notification</span>
      @endif
    </div>

  </div>
</div>
