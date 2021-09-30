<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Lyra;

class CardsController extends Controller {

  /**
   * Get the models from the given resource
   *
   * @param Request $request
   * @param string $resource
   * @return object
   * @throws Exception
   */
  public function index(Request $request, string $resource): object {
    $class = Lyra::resourceBySlug($resource);
    $resource = new $class($class::newModel());
    $cards = collect();

    foreach ($resource->cards() as $class) {
      $cards->push($class->toArray($request));
    }

    return $cards;
  }

  /**
   * Get the given card instance data
   *
   * @param Request $request
   * @param string $resource
   * @param $id
   */
  public function show(Request $request, string $resource, $id) {

  }
}
