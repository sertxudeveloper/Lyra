<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SertxuDeveloper\Lyra\Http\Controllers\Auth\Traits\AuthenticatesUsers;
use SertxuDeveloper\Lyra\Http\Controllers\Controller;
use SertxuDeveloper\Lyra\Lyra;

class LoginController extends Controller {

  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
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
    $this->middleware('guest')->except('logout');
    $this->redirectTo = config('lyra.routes.web.prefix');
    parent::__construct();
  }

  /**
   * Get the guard to be used during authentication.
   *
   * @return \Illuminate\Contracts\Auth\StatefulGuard
   */
  protected function guard() {
    return Lyra::auth();
  }

  /**
   * Attempt to log the user into the application.
   *
   * @param  \Illuminate\Http\Request $request
   * @return bool
   */
  protected function attemptLogin(Request $request) {
    // If the application is in basic mode we will check if the username is in
    // the authorized_users array, if not the login request will be failed.
    if (config('lyra.authenticator') === Lyra::MODE_BASIC) {
      $authorized = array_search($request->get($this->username()), config('lyra.authorized_users'));
      if ($authorized === false) return false;
    }

    setcookie('preferred_theme', $request->get('theme'), strtotime( '+1 year' ));

    return $this->guard()->attempt(
      $this->credentials($request), $request->filled('remember')
    );
  }

  /**
   * Show the application's login form.
   *
   * @return \Illuminate\Http\Response
   */
  public function showLoginForm() {
    if (Auth::check()) return redirect()->route('lyra.dashboard');
    return view('lyra::auth.login');
  }

  /**
   * The user has logged out of the application.
   *
   * @return mixed
   */
  protected function loggedOut()
  {
    return redirect()->route('lyra.login');
  }
}
