<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Lyra;

class ResourceController extends Controller {

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

    $result = $class::$model::query()->paginate();

    return new $class($result);
  }


  /**
   * Create a model with the given resource
   *
   * @param Request $request
   * @param string $resource
   */
  public function create(Request $request, string $resource) {

  }

  /**
   * Get the given resource instance data
   *
   * @param Request $request
   * @param string $resource
   * @param $id
   */
  public function show(Request $request, string $resource, $id) {

  }

  /**
   * Update the given resource instance
   *
   * @param Request $request
   * @param string $resource
   * @param $id
   */
  public function store(Request $request, string $resource, $id) {

  }

  /**
   * Delete the given resource instance
   *
   * @param Request $request
   * @param string $resource
   * @param $id
   */
  public function delete(Request $request, string $resource, $id) {

  }
}
