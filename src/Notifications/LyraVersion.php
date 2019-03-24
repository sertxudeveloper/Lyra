<?php

namespace SertxuDeveloper\Lyra\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LyraVersion extends Notification {
  use Queueable;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct() {
    //
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed $notifiable
   * @return array
   */
  public function via($notifiable) {
    return ['database'];
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed $notifiable
   * @return array
   */
  public function toArray($notifiable) {

    return [
      "title" => "New Lyra version published",
      "message" => "A new version of Lyra is available, you can update it using composer"
    ];
  }
}
