<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use function Couchbase\fastlzDecompress;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use SertxuDeveloper\Lyra\Models\User;

class AuthController extends Controller {

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @param \Illuminate\Http\Request $request
   * @param $user
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  protected function authenticated(Request $request, $user) {
    return redirect(config('lyra.routes.web.prefix'));
  }

  /**
   * Show the application's login form.
   *
   * @return \Illuminate\Http\Response
   */
  public function showLoginForm() {
    if (Auth::check()) {
      return redirect()->route('lyra.dashboard');
    }

    return view('lyra::auth.login');
  }

  /**
   * Get the guard to be used during authentication.
   *
   * @return \Illuminate\Contracts\Auth\StatefulGuard
   */
  protected function guard() {
    return Auth::guard('lyra');
  }

  /**
   * Handle a login request to the application.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function login(Request $request) {
    $this->validateLogin($request);

    // If the class is using the ThrottlesLogins trait, we can automatically throttle
    // the login attempts for this application. We'll key this by the username and
    // the IP address of the client making these requests into this application.
    if ($this->hasTooManyLoginAttempts($request)) {
      $this->fireLockoutEvent($request);

      return $this->sendLockoutResponse($request);
    }

    if ($this->attemptLogin($request)) {
      return $this->sendLoginResponse($request);
    }

    // If the login attempt was unsuccessful we will increment the number of attempts
    // to login and redirect the user back to the login form. Of course, when this
    // user surpasses their maximum number of attempts they will get locked out.
    $this->incrementLoginAttempts($request);

    return $this->sendFailedLoginResponse($request);
  }


  /**
   * Get the failed login response instance.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function sendFailedLoginResponse(Request $request) {
    throw ValidationException::withMessages([
      $this->username() => [trans('lyra::auth.failed')],
    ]);
  }

  /**
   * Redirect the user after determining they are locked out.
   *
   * @param  \Illuminate\Http\Request $request
   * @return void
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function sendLockoutResponse(Request $request) {
    $seconds = $this->limiter()->availableIn(
      $this->throttleKey($request)
    );

    throw ValidationException::withMessages([
      $this->username() => [Lang::get('lyra::auth.throttle', ['seconds' => $seconds])],
    ])->status(429);
  }


  public function showLinkRequestForm() {
    return view('lyra::auth.forgot_password');
  }

  /**
   * Log the user out of the application.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function logout(Request $request) {
    $this->guard()->logout();

    $request->session()->invalidate();

    return $this->loggedOut($request) ?: redirect()->route('lyra.login');
  }
}