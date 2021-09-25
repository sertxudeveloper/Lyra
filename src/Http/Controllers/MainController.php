<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller {

  public function index(Request $request) {
    return view('lyra::dashboard');
  }
}
