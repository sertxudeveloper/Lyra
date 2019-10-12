<?php

namespace SertxuDeveloper\Lyra\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use SertxuDeveloper\Lyra\Lyra;

/**
 * This middleware is used to check if the user is authenticated and has permissions to access to the admin panel
 * @version 1.0
 * @package SertxuDeveloper\Lyra\Http\Middleware
 */
class LyraAdminMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param Illuminate\Http\Request $request
   * @param Closure $next
   *
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if (Lyra::auth()->check()) {
      if (config('lyra.authenticator') == 'basic') {
        return $next($request);
      }

      return (Lyra::auth()->user()->hasPermission('read_lyra')) ? $next($request) : redirect('/');
    }

    return redirect()->route(config('lyra.routes.web.name') . 'login');
  }
}
