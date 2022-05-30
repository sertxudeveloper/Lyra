<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\CollectsResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;
use Illuminate\Support\Str;

class ResourceCollection extends JsonResourceCollection {

  /**
   * Create a new resource instance.
   *
   * @param mixed $collection
   * @param string $class
   */
  public function __construct(mixed $collection, string $class) {
    $this->collects = $class;
    parent::__construct($collection);
  }

  /**
   * Get the resource that this resource collects.
   *
   * @return string|null
   */
  protected function collects(): ?string {
    return $this->collects;
  }

  /**
   * Transform the resource into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /** @var Resource $resource */
    $resource = $this->collects();
    $resource = $resource::make($resource::newModel());

    return [
      'header' => $resource->getHeader($request),
      'data' => $this->collection->map->toArray($request),
      'labels' => ['singular' => $resource::singular(), 'plural' => $resource::label()],
      'perPageOptions' => $resource::$perPageOptions,
      'actions' => ActionResource::collection($resource->actions()),
      'softDeletes' => method_exists($resource::newModel(), 'trashed'),
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
