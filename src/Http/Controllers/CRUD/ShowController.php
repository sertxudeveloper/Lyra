<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
use SertxuDeveloper\Lyra\Lyra;

class ShowController extends DatatypesController {

  public function index(Request $request, string $resource) {
    /** Check if the user has permission to read the $resource requested */
    if (!Lyra::checkPermission('read', $resource)) abort(403);

    if(config('lyra.translator.enabled') && $request->has('lang')) App::setLocale($request->get('lang'));

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $query = $model::query();

    if ($request->get('search')) {
      $search = urldecode($request->get('search'));
      if (preg_match('/^col:[\w]+ [\s\S]*/', $search)) {
        $column = explode('col:', $search)[1];
        $column = explode(' ', $column)[0];

        $search = explode(' ', $search);
        array_shift($search);
        $search = implode(' ', $search);

        $query = $query->orWhere($column, 'like', "%$search%");
      } else {
        foreach ($resourcesNamespace::$search as $key => $column) {
          $query = $query->orWhere($column, 'like', "%$search%");
        }
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

    if ($request->has('perPage')) {
      $query = $query->paginate($request->get('perPage'));
    } else {
      $query = $query->paginate($resourcesNamespace::$perPageOptions[0]);
    }

    $resourceCollection = new $resourcesNamespace($query);
    return $resourceCollection->getCollection($request, 'index');
  }

  public function show(Request $request, string $resource, string $id) {
    /** Check if the user has permission to read the $resource requested */
    if(!Lyra::checkPermission('read', $resource)) abort(403);

    if(config('lyra.translator.enabled') && $request->has('lang')) App::setLocale($request->get('lang'));

    $resourcesNamespace = Lyra::getResources()[$resource];
    $modelClass = $resourcesNamespace::$model;

    if (method_exists($modelClass, 'trashed')) {
      $model = $modelClass::withTrashed()->find($id);
    } else {
      $model = $modelClass::find($id);
    }

    if (!Arr::first($model)) return abort(404, "No query results for model [$modelClass]");
    $resourceCollection = new $resourcesNamespace(collect([$model]));
    return $resourceCollection->getCollection($request, 'show');
  }
}
