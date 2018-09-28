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
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if (!Auth::guest()) {

      /**
       * @TODO
       *
       * If the user access to /lyra/foo and it's not logged,
       * this middleware will return a redirect to the login form route,
       * but once the user has logged in we want to redirect it to the requested route (/lyra/foo)
       * not to the default one (/lyra)
       *
       * We want this:
       * @example /lyra/foo --> /lyra/login --> /lyra/foo
       *
       * Now is doing this:
       * @example /lyra/foo --> /lyra/login --> /lyra
       */

      return (auth()->user()->hasPermission('read_lyra')) ? $next($request) : redirect('/');
    }

    return redirect()->route(config('lyra.routes.name') . 'login');
  }
}
