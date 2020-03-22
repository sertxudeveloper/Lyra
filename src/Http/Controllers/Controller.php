<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use SertxuDeveloper\Lyra\Facades\Lyra;

class Controller extends BaseController {

  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function __construct() {
    Lyra::runObservables();
  }
}
