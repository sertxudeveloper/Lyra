<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Pagination\LengthAwarePaginator;
use SertxuDeveloper\Lyra\Resources\Resource;
use SertxuDeveloper\Lyra\Resources\ResourceCollection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResourceController extends Controller {

  /**
   * Return a new empty resource.
   *
   * @param Request $request
   * @param string $resource
   * @return JsonResponse
   * @throws ResourceNotFoundException
   */
  public function create(Request $request, string $resource): JsonResponse {
    /** @var Resource $class */
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
   * @return Response
   * @throws ResourceNotFoundException
   */
  public function destroy(Request $request, string $resource, mixed $id): Response {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    $model = $class::$model::findOrFail($id);

    abort_if(!$model->delete(), SymfonyResponse::HTTP_NOT_ACCEPTABLE);

    return response()->noContent(SymfonyResponse::HTTP_NO_CONTENT);
  }

  /**
   * Return the specified resource.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse
   * @throws ResourceNotFoundException
   */
  public function edit(Request $request, string $resource, mixed $id): JsonResponse {
    /** @var Resource $class */
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
   * @throws ResourceNotFoundException
   */
  public function index(Request $request, string $resource): JsonResponse {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    $currentPage = $request->query('page') ?: Paginator::resolveCurrentPage();
    $perPage = $request->query('perPage') ?: Arr::first($class::$perPageOptions);
    $options = ['path' => '/', 'pageName' => 'page'];

    $query = $class::$model::query()->with($class::$with);

    /** Add soft deleted if requested and it's supported */
    if (method_exists($class::newModel(), 'trashed')) {
      switch ($request->query('trashed')) {
        case 'with':
          $query->withTrashed();
          break;

        case 'only':
          $query->onlyTrashed();
          break;
      }
    }

    /** Add sort to the resource query */
    if ($request->query('sortBy') && $request->query('sortOrder')) {
      $query = $class::sortResource($request, $query);
    } else {
      /** Add default sorting if defined */
      if ($class::$orderBy)
        $query->orderBy($class::getKeyName(), $class::$orderBy);
    }

    if ($request->has('q')) {
      $query = $class::searchInResource($request, $query);
    }

    $total = $query->toBase()->getCountForPagination();

    $items = $total ? $query->forPage($currentPage, $perPage)->get() : (new $class::$model)->newCollection();

    $pagination = new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);
    $response = ResourceCollection::make($pagination, $class);

    return $response->toResponse($request);
  }

  /**
   * Restore the specified resource.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return Response
   * @throws ResourceNotFoundException
   */
  public function restore(Request $request, string $resource, mixed $id): Response {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    $model = $class::$model::onlyTrashed()->findOrFail($id);

    abort_if(!$model->restore(), SymfonyResponse::HTTP_NOT_ACCEPTABLE);

    return response()->noContent(SymfonyResponse::HTTP_NO_CONTENT);
  }

  /**
   * Display the specified resource.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse
   * @throws ResourceNotFoundException
   */
  public function show(Request $request, string $resource, mixed $id): JsonResponse {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    $query = $class::$model::query()->with($class::$with);

    /** Include soft deleted if it's supported */
    if (method_exists($class::newModel(), 'trashed')) {
      $query->withTrashed();
    }

    $model = $query->findOrFail($id);

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
   * @return JsonResponse|Response
   * @throws ResourceNotFoundException
   */
  public function store(Request $request, string $resource): Response|JsonResponse {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    $model = new $class::$model;
    $resource = new $class($model);

    $validated = $resource->validateCreation($request);

    foreach ($validated as $key => $value) {
      $model->$key = $value;
    }

    abort_if(!$model->save(), SymfonyResponse::HTTP_NOT_ACCEPTABLE);

    return response()->json([
      'data' => $class::make($model->fresh())->toArray($request),
      'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
    ], SymfonyResponse::HTTP_CREATED);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param string $resource
   * @param mixed $id
   * @return JsonResponse|Response
   * @throws ResourceNotFoundException
   */
  public function update(Request $request, string $resource, mixed $id): Response|JsonResponse {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    $model = $class::$model::findOrFail($id);
    $resource = new $class($model);

    $validated = $resource->validateUpdate($request);

    /** Check if the model has been modified since the retrieval */
    if (Carbon::make($request->input('updated_at'))->notEqualTo($model->{$model->getUpdatedAtColumn()}))
      return response()->noContent(SymfonyResponse::HTTP_CONFLICT);

    foreach ($resource->fields() as $field) {
      if (!isset($validated[$field->getKey()])) continue;
      $field->save($model, $validated);
    }

    abort_if(!$model->save(), SymfonyResponse::HTTP_NOT_ACCEPTABLE);

    return response()->json([
      'data' => $class::make($model->fresh())->toArray($request),
      'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
    ], SymfonyResponse::HTTP_ACCEPTED);
  }
}
