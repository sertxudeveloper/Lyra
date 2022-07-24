<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SertxuDeveloper\Lyra\Actions\Action;
use SertxuDeveloper\Lyra\Exceptions\ActionNotFoundException;
use SertxuDeveloper\Lyra\Exceptions\ResourceNotFoundException;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResourceActionController extends Controller
{
    /**
     * @throws ResourceNotFoundException
     * @throws ActionNotFoundException
     */
    public function exec(Request $request, string $resource): Response {
        /** @var Resource $class */
        $class = Lyra::resourceBySlug($resource);

        abort_if(!$request->has('models'), SymfonyResponse::HTTP_BAD_REQUEST);

        $models = $class::$model::find($request->input('models'));

        $resource = new $class($request);
        $action = $this->findActionInResource($resource, $request->input('action'));

        $models->each(function ($model) use ($action) {
            $action::dispatch($model);
        });

        return response()->noContent(SymfonyResponse::HTTP_ACCEPTED);
    }

    /**
     * Find the requested action in the provided resource
     *
     * @param  resource  $resource
     * @param  string  $actionSlug
     * @return Action
     *
     * @throws ActionNotFoundException
     */
    private function findActionInResource(Resource $resource, string $actionSlug): Action {
        foreach ($resource->actions() as $action) {
            if ($action::slug() === $actionSlug) {
                return $action;
            }
        }

        throw new ActionNotFoundException;
    }
}
