<?php

namespace SertxuDeveloper\Lyra\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use SertxuDeveloper\Lyra\Lyra;

/**
 * This middleware is used to check if the user is authenticated while using the API service
 * @version 1.0
 * @package SertxuDeveloper\Lyra\Http\Middleware
 */
class LyraApiAdminMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure $next
   *
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if (Lyra::auth()->check()) {
      return $next($request);
    }

    return abort(403);
  }
}
