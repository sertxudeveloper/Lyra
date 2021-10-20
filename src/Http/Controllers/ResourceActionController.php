<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use SertxuDeveloper\Lyra\Actions\Action;
use SertxuDeveloper\Lyra\Lyra;
use SertxuDeveloper\Lyra\Resources\Resource;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResourceActionController extends Controller {

  /**
   * @throws Exception
   */
  public function exec(Request $request, string $resource): Response {
    /** @var Resource $class */
    $class = Lyra::resourceBySlug($resource);

    if (!$request->has('models'))
      return response()->noContent(SymfonyResponse::HTTP_BAD_REQUEST);

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
   * @param Resource $resource
   * @param string $actionSlug
   * @return Action
   * @throws Exception
   */
  private function findActionInResource(Resource $resource, string $actionSlug): Action {
    foreach ($resource->actions() as $action) {
      if ($action::slug() === $actionSlug) return $action;
    }

    throw new Exception('Action not found');
  }
}
