<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Http\Resources\LyraNotifications;
use SertxuDeveloper\Lyra\Lyra;

class NotificationsController extends Controller {

  public function index(Request $request) {
    $notifications = Lyra::auth()->user()->unreadNotifications;
    return LyraNotifications::collection($notifications);
  }

  public function read(Request $request, string $id) {
    Lyra::auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    return $this->index($request);
  }
}
