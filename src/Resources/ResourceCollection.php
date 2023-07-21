<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;

class ResourceCollection extends JsonResourceCollection
{
    /**
     * Create a new resource instance.
     */
    public function __construct(mixed $collection, string $class) {
        $this->collects = $class;
        parent::__construct($collection);
    }

    /**
     * Get the resource that this resource collects.
     */
    protected function collects(): ?string {
        return $this->collects;
    }

    /**
     * Transform the resource into a JSON array.
     */
    public function toArray(Request $request): array {
        /** @var resource $resource */
        $resource = $this->collects();
        $resource = $resource::make($resource::newModel());

        //        dd($this->collection, $this->collection->map->serializeForIndex($request));

        return [
            //            'header' => $resource->getHeader($request),
            'labels' => ['singular' => $resource::singular(), 'plural' => $resource::label()],
            'data' => $this->collection->map->serializeForIndex($request),
            'perPageOptions' => $resource::$perPageOptions,
            'actions' => ActionResource::collection($resource->actions()),
            'softDeletes' => method_exists($resource::newModel(), 'trashed'),
        ];
    }

    /**
     * Create a paginate-aware HTTP response.
     *
     * @param  Request  $request
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
