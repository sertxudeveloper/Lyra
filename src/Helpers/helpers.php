<?php

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

if (!function_exists('lyra_auth')) {
  function lyra_auth() {
    if (config('lyra.authenticator') == 'basic') {
      return auth();
    } else if (config('lyra.authenticator') == 'lyra') {
      return auth()->guard('lyra');
    }
    return null;
  }
}
