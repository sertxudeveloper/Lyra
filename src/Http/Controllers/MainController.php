<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Show Lyra base view.
     */
    public function index(Request $request): View {
        //    dd($request->user(), $request->user('lyra'), config('lyra.auth'));
        return view('lyra::base');
    }
}
