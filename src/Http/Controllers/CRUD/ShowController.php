<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
use SertxuDeveloper\Lyra\Lyra;

class ShowController extends DatatypesController {

  public function index(Request $request, string $resource) {
    /** Check if the user has permission to read the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('read_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $query = $model::query();

    if ($request->get('search')) {
      $search = urldecode($request->get('search'));
      foreach ($resourcesNamespace::$search as $key => $column) {
        $query = $query->orWhere($column, 'like', "%$search%");
      }
    }

    if ($request->get('sortCol') && $request->get('sortDir')) {
      $sortCol = explode(',', $request->get('sortCol'));
      $sortDir = explode(',', $request->get('sortDir'));
      foreach ($sortCol as $key => $value) {
        $query = $query->orderBy($sortCol[$key], $sortDir[$key]);
      }
    }

    $query = $this->checkSoftDeletes($request, $query, $model);
    $query = $request->has('perPage') ? $query->paginate($request->get('perPage')) : $query->paginate(25);

    $resourceCollection = new $resourcesNamespace($query);
    return $resourceCollection->getCollection($request, 'index');
  }

  public function show(Request $request, string $resource, string $id) {
    /** Check if the user has permission to read the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('read_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;

    if (method_exists($model, 'trashed')) {
      $element = $model::where($resourcesNamespace::getPrimary(), $id)->withTrashed()->get();
    } else {
      $element = $model::where($resourcesNamespace::getPrimary(), $id)->get();
    }

    if (!Arr::first($element)) return abort(404, "No query results for model [$model]");
    $resourceCollection = new $resourcesNamespace($element);
    return $resourceCollection->getCollection($request, 'show');
  }
}
