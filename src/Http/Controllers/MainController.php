<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use SertxuDeveloper\Lyra\Facades\Lyra;

class MainController extends Controller {

  /**
   * Return the main Lyra view
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index($any = false) {
    if ($any === 'media' && config('lyra.authenticator') === 'lyra') {
      if (!Lyra::auth()->user()->hasPermission('read', $any)) abort(403);
    }
    return view('lyra::index');
  }

  /**
   * Return the Lyra terms and conditions
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function showTerms() {
    return view('lyra::terms');
  }

  /**
   * Return the Lyra privacy policy
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function showPrivacy() {
    return view('lyra::privacy');
  }
}
