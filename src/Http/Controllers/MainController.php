<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

class MainController extends Controller {

  /**
   * Return the main Lyra view
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index() {
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