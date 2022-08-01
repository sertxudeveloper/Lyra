<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;

class ResourceEditController extends Controller
{
    /**
     * Return the specified resource.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  mixed  $id
     * @return JsonResponse
     *
     * @throws ResourceNotFoundException
     */
    public function edit(Request $request, string $resource, mixed $id): JsonResponse {
        /** @var Resource $class */
        $class = Lyra::resourceBySlug($resource);

        $model = $class::$model::findOrFail($id);

        return response()->json([
            'data' => $class::make($model)->toArray($request),
            'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  mixed  $id
     * @return JsonResponse|Response
     *
     * @throws ResourceNotFoundException
     */
    public function update(Request $request, string $resource, mixed $id): Response|JsonResponse {
        /** @var Resource $class */
        $class = Lyra::resourceBySlug($resource);

        $model = $class::$model::findOrFail($id);
        $resource = new $class($model);

        $validated = $resource->validateUpdate($request);

        /** Check if the model has been modified since the retrieval */
        if (Carbon::make($request->input('updated_at'))->notEqualTo($model->{$model->getUpdatedAtColumn()})) {
            return response()->noContent(Response::HTTP_CONFLICT);
        }

        foreach ($resource->fields() as $field) {
            if (!isset($validated[$field->getKey()])) {
                continue;
            }

            $field->save($model, $validated);
        }

        abort_if(!$model->save(), Response::HTTP_NOT_ACCEPTABLE);

        return response()->json([
            'data' => $class::make($model->fresh())->toArray($request),
            'labels' => ['singular' => $class::singular(), 'plural' => $class::label()],
        ], Response::HTTP_ACCEPTED);
    }
}
