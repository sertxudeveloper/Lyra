<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;

class ResourceCreateController extends Controller
{
    /**
     * Return a new empty resource.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @return JsonResponse
     *
     * @throws ResourceNotFoundException
     */
    public function __invoke(Request $request, string $resource): JsonResponse {
        /** @var Resource $class */
        $class = Lyra::resourceBySlug($resource);

        $model = new $class::$model;

        return response()->json([
            'data' => $class::make($model)->serializeForCreate($request),
            'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
        ]);
    }
}
