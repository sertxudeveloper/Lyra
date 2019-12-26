<?php

namespace SertxuDeveloper\Lyra\Traits;

use Illuminate\Notifications\RoutesNotifications;

trait Notifiable {

  use HasDatabaseNotifications, RoutesNotifications;
}
