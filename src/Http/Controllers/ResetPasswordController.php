<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use SertxuDeveloper\Lyra\Http\Controllers\Auth\ResetsPasswords;
use SertxuDeveloper\Lyra\Lyra;

class ResetPasswordController extends Controller {
  /*
  |--------------------------------------------------------------------------
  | Password Reset Controller
  |--------------------------------------------------------------------------
  |
  | This controller is responsible for handling password reset requests
  | and uses a simple trait to include this behavior. You're free to
  | explore this trait and override any methods you wish to tweak.
  |
  */

  use ResetsPasswords;

  /**
   * Where to redirect users after resetting their password.
   *
   * @var string
   */
  protected $redirectTo;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->redirectTo = config('lyra.routes.web.prefix');
    parent::__construct();
  }

  /**
   * Display the password reset view for the given token.
   *
   * If no token is present, display the link request form.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  string|null $token
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function showResetForm(Request $request, $token = null) {
    return view('lyra::auth.passwords.reset')->with(
      ['token' => $token, 'email' => $request->email]
    );
  }

//  /**
//   * Attempt to log the user into the application.
//   *
//   * @param  \Illuminate\Http\Request $request
//   * @return bool
//   * @throws ValidationException
//   */
//  protected function attemptLogin(Request $request) {
//    // If the application is in basic mode we will check if the username is in
//    // the authorized_users array, if not the login request will be failed.
//    if (config('lyra.authenticator') === Lyra::MODE_BASIC) {
//      $authorized = array_search($request->get($this->username()), config('lyra.authorized_users'));
//      if ($authorized === false) return $this->sendFailedLoginResponse($request);
//    }
//
//    return $this->guard()->attempt(
//      $this->credentials($request), $request->filled('remember')
//    );
//  }

  /**
   * Reset the given user's password.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  public function reset(Request $request) {
    $request->validate($this->rules(), $this->validationErrorMessages());

    // If the application is in basic mode we will check if the username is in
    // the authorized_users array, if not the login request will be failed.
    if (config('lyra.authenticator') === Lyra::MODE_BASIC) {
      $authorized = array_search($request->get($this->username()), config('lyra.authorized_users'));
      if ($authorized === false) return $this->sendResetFailedResponse($request, back()->with('email', trans('auth.failed')));
    }

    // Here we will attempt to reset the user's password. If it is successful we
    // will update the password on an actual user model and persist it to the
    // database. Otherwise we will parse the error and return the response.
    $response = $this->broker()->reset(
      $this->credentials($request), function ($user, $password) {
      $this->resetPassword($user, $password);
    });

    // If the password was successfully reset, we will redirect the user back to
    // the application's home authenticated view. If there is an error we can
    // redirect them back to where they came from with their error message.
    return $response == Password::PASSWORD_RESET
      ? $this->sendResetResponse($request, $response)
      : $this->sendResetFailedResponse($request, $response);
  }

  /**
   * Get the login username to be used by the controller.
   *
   * @return string
   */
  public function username() {
    return 'email';
  }

  /**
   * Get the guard to be used during password reset.
   *
   * @return \Illuminate\Contracts\Auth\StatefulGuard
   */
  protected function guard() {
    return Lyra::auth();
  }

}
