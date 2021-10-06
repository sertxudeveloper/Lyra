<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Pagination\LengthAwarePaginator;

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

//    $items = $class::$model::all();
//    $total = $items->count();


    $currentPage = $request->input('page') ?: Paginator::resolveCurrentPage();
    $perPage = $request->input('perPage') ?: (new $class::$model)->getPerPage();
    $options = ['path' => '/', 'pageName' => 'page'];

    $query = $class::$model::query();
    $total = $query->toBase()->getCountForPagination();

    $items = $total ? $query->forPage($currentPage, $perPage)->get('*') : (new $class::$model)->newCollection();

    $pagination = new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);

    return new $class($pagination);
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
