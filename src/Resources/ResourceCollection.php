<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;

class ResourceCollection extends JsonResourceCollection
{
    /**
     * Indicates if all existing request query parameters should be added to pagination links.
     *
     * @var bool
     */
    protected $preserveAllQueryParameters = true;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $collection
     * @param  string  $class
     */
    public function __construct(mixed $collection, string $class) {
        $this->collects = $class;
        parent::__construct($collection);
    }

    /**
     * Transform the resource into a JSON array.
     *
     * @param  Request  $request
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
            'actions' => ActionResource::collection($resource->actions()) ?? [],
            'softDeletes' => method_exists($resource::newModel(), 'trashed'),
        ];
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
     * Create a paginate-aware HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    protected function preparePaginatedResponse($request): JsonResponse {
        parent::preparePaginatedResponse($request);

        return (new Pagination($this))->toResponse($request);
    }
}
