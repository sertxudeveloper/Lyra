<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;

class ResourceCollection extends JsonResourceCollection {

  /** @var string */
  public string $class;

  /**
   * Create a new resource instance.
   *
   * @param string $collects
   * @param mixed $resource
   * @return void
   */
  public function __construct(string $collects, mixed $resource) {
    $this->collects = $collects;
    parent::__construct($resource);
  }

  /**
   * Transform the resource into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /** @var Resource $resource */
    $resource = new $this->collects($request);

    return [
      'header' => $resource::make($resource::newModel())->toTableHeader($request),
      'data' => $resource::collection($this->collection),
      'labels' => ['singular' => $resource::singular(), 'plural' => $resource::label()],
      'perPageOptions' => $resource::$perPageOptions,
      'actions' => ActionResource::collection($resource->actions())
    ];
  }

  /**
   * Create a paginate-aware HTTP response.
   *
   * @param Request $request
   * @return JsonResponse
   */
  protected function preparePaginatedResponse($request): JsonResponse {
    if ($this->preserveAllQueryParameters) {
      $this->resource->appends($request->query());
    } elseif (!is_null($this->queryParameters)) {
      $this->resource->appends($this->queryParameters);
    }

    return (new Pagination($this))->toResponse($request);
  }
}
