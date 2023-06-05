<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Show Lyra base view.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View {
        return view('lyra::base');
    }
}
