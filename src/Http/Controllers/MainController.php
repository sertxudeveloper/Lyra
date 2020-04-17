<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use SertxuDeveloper\Lyra\Lyra;

class MainController extends Controller {

  /**
   * Return the main Lyra view
   * @param string $any
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index($any = '') {
    if ($any === 'media' && config('lyra.authenticator') === 'lyra') {
      if (!Lyra::auth()->user()->hasPermission('read', $any)) abort(403);
    }
    return view('lyra::index');
  }

}
