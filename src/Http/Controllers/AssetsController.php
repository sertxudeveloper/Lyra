<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use SertxuDeveloper\Lyra\Lyra;

class AssetsController extends Controller {

  /**
   * Return the style file content
   * @param string $name
   * @return mixed
   */
  public function style($name) {
    $url = Lyra::allStyles()[$name] ?? null;
    if (!$url || !file_exists($url)) abort(404);

    return response(file_get_contents($url), 200, ['Content-Type' => 'text/css']);
  }

  /**
   * Return the script file content
   * @param string $name
   * @return mixed
   */
  public function script($name) {
    $url = Lyra::allScripts()[$name] ?? null;
    if (!$url || !file_exists($url)) abort(404);

    return response(file_get_contents($url), 200, ['Content-Type' => 'application/javascript']);
  }

  /**
   * Return the asset file content
   * @param string $name
   * @return mixed
   */
  public function asset($name) {
    $url = Lyra::allAssets()[$name] ?? null;
    if (!$url || !file_exists($url)) abort(404);

    return response(file_get_contents($url), 200, ['Content-Type' => mime_content_type($url)]);
  }

}
