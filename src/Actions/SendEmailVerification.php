<?php

namespace SertxuDeveloper\Lyra\Actions;

class SendEmailVerification extends Action {

  public function handle() {
    $this->model->sendEmailVerificationNotification();
  }
}
