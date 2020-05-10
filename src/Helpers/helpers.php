<?php

use SertxuDeveloper\Lyra\Lyra;

if (!function_exists('lyra_asset')) {
  function lyra_asset($path) {
    return asset(config('lyra.assets_path') . '/' . $path);
  }
}

if (!function_exists('lyra_route')) {
  function lyra_route($name) {
    return route(config('lyra.routes.web.name') . $name);
  }
}

if (!function_exists('lyra_theme')) {
  function lyra_theme() {
    if (config('lyra.authenticator') === Lyra::MODE_ADVANCED && auth()->guard('lyra')->user()) {
      return auth()->guard('lyra')->user()->preferred_theme ?? 'default';
    }
    return $_COOKIE['preferred_theme'] ?? 'default';
  }
}
