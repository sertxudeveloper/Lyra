<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;

class ResourceDeleteController extends Controller
{
    /**
     * Remove the specified resource.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  mixed  $id
     * @return Response
     *
     * @throws ResourceNotFoundException
     */
    public function destroy(Request $request, string $resource, mixed $id): Response {
        /** @var resource $class */
        $class = Lyra::resourceBySlug($resource);

        $model = $class::$model::findOrFail($id);

        abort_if(!$model->delete(), Response::HTTP_NOT_ACCEPTABLE);

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified resource.
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  mixed  $id
     * @return Response
     *
     * @throws ResourceNotFoundException
     */
    public function restore(Request $request, string $resource, mixed $id): Response {
        /** @var resource $class */
        $class = Lyra::resourceBySlug($resource);

        $model = $class::$model::onlyTrashed()->findOrFail($id);

        abort_if(!$model->restore(), Response::HTTP_NOT_ACCEPTABLE);

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }
}
