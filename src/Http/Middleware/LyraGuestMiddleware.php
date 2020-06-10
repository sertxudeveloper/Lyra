<?php

namespace SertxuDeveloper\Lyra\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Lyra;

/**
 * This middleware is used to check if the user is not authenticated
 * @version 1.0
 * @package SertxuDeveloper\Lyra\Http\Middleware
 */
class LyraGuestMiddleware {

  /**
   * Where to redirect if the user is logged in.
   *
   * @var string
   */
  protected $redirectTo;

  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   *
   * @return mixed
   */
  public function handle(Request $request, Closure $next) {
    if (Lyra::auth()->check()) {
      $this->redirectTo = config('lyra.routes.web.prefix');
      return redirect($this->redirectTo);
    }

    return $next($request);
  }
}
