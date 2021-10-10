<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;

class ResourceCollection extends JsonResourceCollection {

  /**
   * @var string
   */
  public string $class;

  /**
   * Create a new resource instance.
   *
   * @param string $collects
   * @param mixed $resource
   * @return void
   */
  public function __construct(string $collects, $resource) {
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

    return [
      'data' => $this->collects::collection($this->collection),
      'labels' => ['singular' => $this->collects::singular(), 'plural' => $this->collects::label()],
      'perPageOptions' => $this->collects::$perPageOptions
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
