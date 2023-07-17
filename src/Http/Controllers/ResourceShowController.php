<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;

class ResourceShowController extends Controller
{
    /**
     * Display the specified resource.
     *
     *
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
}
