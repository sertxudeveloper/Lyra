<?php

namespace SertxuDeveloper\Lyra\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * This middleware is used to check if the user is authenticated and has permissions to access to the admin panel
 * @version 1.0
 * @package SertxuDeveloper\Lyra\Http\Middleware
 */
class LyraAdminMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure $next
   *
   * @param  string|null $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = 'lyra') {
    if (Auth::guard($guard)->check()) {
      return (auth()->guard('lyra')->user()->hasPermission('read_lyra')) ? $next($request) : redirect('/');
    }

    return redirect()->route(config('lyra.routes.web.name') . 'login');
  }
}
