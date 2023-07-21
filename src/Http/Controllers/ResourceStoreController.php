<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;

class ResourceStoreController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @return JsonResponse|Response
     *
     * @throws ResourceNotFoundException
     */
    public function __invoke(Request $request, string $resource): JsonResponse {
        /** @var Resource $class */
        $class = Lyra::resourceBySlug($resource);

        $model = new $class::$model;
        $resource = new $class($model);

        $validated = $resource->validateCreation($request);

        foreach ($validated as $key => $value) {
            $model->$key = $value;
        }

        abort_if(!$model->save(), Response::HTTP_NOT_ACCEPTABLE);

        return response()->json([
            'key' => $model->getKey(),
        ], Response::HTTP_CREATED);
    }
}
