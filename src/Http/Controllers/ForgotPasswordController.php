<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use SertxuDeveloper\Lyra\Http\Controllers\Auth\SendsPasswordResetEmails;
use SertxuDeveloper\Lyra\Lyra;

class ForgotPasswordController extends Controller {
  /*
  |--------------------------------------------------------------------------
  | Password Reset Controller
  |--------------------------------------------------------------------------
  |
  | This controller is responsible for handling password reset emails and
  | includes a trait which assists in sending these notifications from
  | your application to your users. Feel free to explore this trait.
  |
  */

  use SendsPasswordResetEmails;

  /**
   * Display the form to request a password reset link.
   *
   * @return \Illuminate\Http\Response
   */
  public function showLinkRequestForm() {
    return view('lyra::auth.passwords.email');
  }

  /**
   * Get the broker to be used during password reset.
   *
   * @return \Illuminate\Contracts\Auth\PasswordBroker
   */
  public function broker() {
    return Lyra::broker();
  }
}
