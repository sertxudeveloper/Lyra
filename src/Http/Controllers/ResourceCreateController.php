<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
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
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @return JsonResponse|Response
     *
     * @throws ResourceNotFoundException|ValidationException
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

        abort_if(!$model->save(), Response::HTTP_NOT_ACCEPTABLE);

        return response()->json([
            'data' => $class::make($model->fresh())->toArray($request),
            'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
        ], Response::HTTP_CREATED);
    }
}
