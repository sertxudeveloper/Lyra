<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller {

  public function index(Request $request) {
    $dashboardClass = config('lyra.dashboard');
    $dashboard = new $dashboardClass();
    return $dashboard->toArray($request);
  }
}
