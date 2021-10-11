<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Pagination\LengthAwarePaginator;
use SertxuDeveloper\Lyra\Resources\ResourceCollection;

class ResourceController extends Controller {

  /**
   * Return a new empty resource.
   *
   * @param Request $request
   * @param string $resource
   * @return JsonResponse
   * @throws Exception
   */
  public function create(Request $request, string $resource): JsonResponse {
    $class = Lyra::resourceBySlug($resource);

    $model = new $class::$model;

    return response()->json([
      'data' => $class::make($model)->toArray($request),
      'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
    ]);
  }

  /**
   * Remove the specified resource.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse
   */
  public function destroy(Request $request, string $resource, $id): JsonResponse {
    //
  }

  /**
   * Return the specified resource.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse
   * @throws Exception
   */
  public function edit(Request $request, string $resource, $id): JsonResponse {
    $class = Lyra::resourceBySlug($resource);

    $model = $class::$model::findOrFail($id);

    return response()->json([
      'data' => $class::make($model)->toArray($request),
      'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
    ]);
  }

  /**
   * Return a collection of resources.
   *
   * @param Request $request
   * @param string $resource
   * @return JsonResponse
   * @throws Exception
   */
  public function index(Request $request, string $resource): JsonResponse {
    $class = Lyra::resourceBySlug($resource);

    $currentPage = $request->input('page') ?: Paginator::resolveCurrentPage();
    $perPage = $request->input('perPage') ?: Arr::first($class::$perPageOptions);
    $options = ['path' => '/', 'pageName' => 'page'];

    $query = $class::$model::query();
    if ($class::$orderBy) $query->orderBy($class::getKeyName(), $class::$orderBy);
    $total = $query->toBase()->getCountForPagination();

    $items = $total ? $query->forPage($currentPage, $perPage)->get('*') : (new $class::$model)->newCollection();

    $pagination = new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);
    $response = ResourceCollection::make($class, $pagination);

    return $response->toResponse($request);
  }

  /**
   * Display the specified resource.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse
   * @throws Exception
   */
  public function show(Request $request, string $resource, $id): JsonResponse {
    $class = Lyra::resourceBySlug($resource);

    $model = $class::$model::findOrFail($id);

    return response()->json([
      'data' => $class::make($model)->toArray($request),
      'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @param string $resource
   * @return JsonResponse
   */
  public function store(Request $request, string $resource): JsonResponse {
    $class = Lyra::resourceBySlug($resource);

    $model = new $class::$model;
    $resource = new $class($model);

    $fields = [];
    foreach ($resource->fields() as $field) {
      if (!$field->canShow($request)) continue;
//      $fields[] = $field->toArray($this->resource);
      dd($field);
      $fields[$field->column] = $request->get($field->key);
    }

    dd('end', $fields);

//    dd($resource->update($request));

//    foreach ($request->except('updated_at') as $attribute => $value) {
//      $model->setAttribute();
//    }

//    dd($request->all(), $model, $model->getAttributes(), $model->getFillable(), $model->getGuarded());
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse
   * @throws Exception
   */
  public function update(Request $request, string $resource, $id): JsonResponse {
    $class = Lyra::resourceBySlug($resource);

    $model = $class::$model::findOrFail($id);
  }
}
