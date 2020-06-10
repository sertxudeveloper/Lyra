<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\Auth;


use SertxuDeveloper\Lyra\Http\Controllers\Auth\Traits\SendsPasswordResetEmails;
use SertxuDeveloper\Lyra\Http\Controllers\Controller;
use SertxuDeveloper\Lyra\Lyra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

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
   * Get the broker to be used during password reset.
   *
   * @return \Illuminate\Contracts\Auth\PasswordBroker
   */
  public function broker() {
    return Lyra::broker();
  }

  /**
   * Send a reset link to the given user.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  public function sendResetLinkEmail(Request $request) {
    $this->validateEmail($request);

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $response = $this->broker()->sendResetLink(
      $this->credentials($request)
    );

    return $response == Password::RESET_LINK_SENT
      ? $this->sendResetLinkResponse($request, "lyra::$response")
      : $this->sendResetLinkFailedResponse($request, "lyra::$response");
  }

  /**
   * Display the form to request a password reset link.
   *
   * @return \Illuminate\Http\Response
   */
  public function showLinkRequestForm() {
    return view('lyra::auth.passwords.email');
  }

}
