<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification {

  protected $table = 'lyra_notifications';

}
