<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Facades\Lyra;
use SertxuDeveloper\Lyra\Pagination\LengthAwarePaginator;
use SertxuDeveloper\Lyra\Resources\ResourceCollection;

class ResourceController extends Controller
{
    /**
     * Return a collection of resources.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @return JsonResponse
     *
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
            if ($class::$orderBy) {
                $query->orderBy($class::getKeyName(), $class::$orderBy);
            }
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
}
