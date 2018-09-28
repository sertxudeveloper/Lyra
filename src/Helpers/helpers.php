<?php

if (!function_exists('lyra_asset')) {
  function lyra_asset($path) {
    return asset(config('lyra.assets_path') . '/' . $path);
  }
}

if (!function_exists('lyra_route')) {
  function lyra_route($name) {
    return route(config('lyra.routes.name') . $name);
  }
}
