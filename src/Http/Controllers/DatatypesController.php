<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Lyra;

class DatatypesController extends CrudController {

  public function index(Request $request, string $resource) {
    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $query = $model;
    if ($request->get('search')) {
      $search = urldecode($request->get('search'));
      foreach ($resourcesNamespace::$search as $key => $column) {
        $query = ($key === 0) ? $query = $query::orWhere($column, 'like', "%$search%") : $query = $query->orWhere($column, 'like', "%$search%");
      }
      $query = $this->checkSoftDeletes($request, $query, $model);
      $query = $query->paginate($request->get('perPage'));
    } else {
      $query = $this->checkSoftDeletes($request, $query, $model);
      $query = ($query === $model) ? $query::paginate($request->get('perPage')) : $query->paginate($request->get('perPage'));
    }

    $resourceCollection = new $resourcesNamespace($query);
    if (!auth()->guard('lyra')->user()->hasPermission('read_' . $resource)) abort(403);
    return $resourceCollection->getCollection('index');
  }

  public function show(string $resource, string $id) {
    $resourcesNamespace = '\SertxuDeveloper\Lyra\Http\Resources\\' . ucfirst($resource);
    $model = $resourcesNamespace::$model;
    $resourceCollection = new $resourcesNamespace($model::where($resourcesNamespace::$primary, $id)->get());
    if (!auth()->guard('lyra')->user()->hasPermission('read_' . $resource)) abort(403);
    return $resourceCollection->getCollection('show');
  }

  public function create(string $resource) {
    $resourcesNamespace = '\SertxuDeveloper\Lyra\Http\Resources\\' . ucfirst($resource);
    $model = $resourcesNamespace::$model;
    $resourceCollection = new $resourcesNamespace(new $model);
    if (!auth()->guard('lyra')->user()->hasPermission('create_' . $resource)) abort(403);
    return $resourceCollection->getCollection('create');
  }

  private function checkSoftDeletes(Request $request, $query, $model) {
    if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
      switch ($request->get('visibility')) {
        case 'trashed':
          return ($query === $model) ? $query::onlyTrashed() : $query->onlyTrashed();
          break;
        case 'all':
          return ($query === $model) ? $query::withTrashed() : $query->withTrashed();
          break;
        default:
          return $query;
          break;
      }
    }
    return $query;
  }
}