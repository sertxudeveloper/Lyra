<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Lyra;

class DatatypesController extends CrudController {

  /**
   * @param Request $request
   * @param string $resource
   * @return mixed
   */
  public function index(Request $request, string $resource) {
    /** Check if the user has permission to read the $resource requested */
    if (!auth()->guard('lyra')->user()->hasPermission('read_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $query = $model::query();

    if ($request->get('search')) {
      $search = urldecode($request->get('search'));
      foreach ($resourcesNamespace::$search as $key => $column) {
        $query = $query->orWhere($column, 'like', "%$search%");
      }
    }

    $query = $this->checkSoftDeletes($request, $query, $model);
    $query = $request->has('perPage') ? $query->paginate($request->get('perPage')) : $query->paginate(25);

    $resourceCollection = new $resourcesNamespace($query);
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
          return $query->onlyTrashed();
          break;
        case 'all':
          return $query->withTrashed();
          break;
        default:
          return $query;
          break;
      }
    }

    return $query;
  }
}