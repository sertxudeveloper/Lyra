<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Lyra;

class DatatypesController extends CrudController {

  /**
   * @param Request $request
   * @param string $resource
   * @return Collection
   */
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

  /**
   * @param Request $request
   * @param string $resource
   * @param string $id
   * @return mixed
   */
  public function show(Request $request, string $resource, string $id) {
    /** Check if the user has permission to read the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('read_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $resourceCollection = new $resourcesNamespace($model::where($resourcesNamespace::getPrimary(), $id)->get());
    return $resourceCollection->getCollection($request, 'show');
  }

  /**
   * @param Request $request
   * @param string $resource
   * @param string $id
   * @return mixed
   */
  public function edit(Request $request, string $resource, string $id) {
    /** Check if the user has permission to edit the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('edit_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $resourceCollection = new $resourcesNamespace($model::where($resourcesNamespace::getPrimary(), $id)->get());
    return $resourceCollection->getCollection($request, 'edit');
  }

  /**
   * @param Request $request
   * @param string $resource
   * @return mixed
   */
  public function create(Request $request, string $resource) {
    /** Check if the user has permission to create the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('create_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $resourceCollection = new $resourcesNamespace(new $model);
    return $resourceCollection->getCollection($request, 'create');
  }

  public function destroy(Request $request, string $resource, string $id) {
    /** Check if the user has permission to delete the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('delete_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $element = $model::where($resourcesNamespace::getPrimary(), $id);
    if (!$element) return abort(404);
    $element->delete();
    return null;
  }

  /**
   * @param Request $request
   * @param string $resource
   *
   * @return null
   */
  public function store(Request $request, string $resource) {
    /** Check if the user has permission to create the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('create_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $collection = $request->post('collection');

    $fields = collect(Arr::first($collection['data']));

    $element = new $model;
    $fields->each(function ($item, $key) use ($element) {
      $element[$item['column']] = $item['value'];
    });

    $element->saveOrFail();
    return null;
  }

  public function update(Request $request, string $resource, string $id) {
    /** Check if the user has permission to create (update) the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('create_' . $resource)) abort(403);

//    $resourcesNamespace = Lyra::getResources()[$resource];
//    $model = $resourcesNamespace::$model;
//    $collection = $request->post('collection');
//
//    $fields = collect(Arr::first($collection['data']));
//
//    $element = $model::where($resourcesNamespace::getPrimary(), $id)->first();
//    $fields->each(function ($item, $key) use ($element) {
//      $element[$item['column']] = $item['value'];
//    });
//
//    $element->saveOrFail();
//    return null;

    $resourcesNamespace = Lyra::getResources()[$resource];
    $resource = $resourcesNamespace::$model::where($resourcesNamespace::getPrimary(), $id)->first();

    $collection = $request->post('collection');
    $values = collect(Arr::first($collection['data']));

    $fields = $resourcesNamespace::getFields($resource);

    $fields = $fields->filter(function ($field) {
      $permission = $field->getPermissions();
      return !$permission['hideOnEdit'];
    })->values();

    $fields->each(function ($field, $key) use ($values, $resource) {
      $resource[$values[$key]['column']] = $field->updateValue($values[$key]['value']);
    });

    $resource->saveOrFail();
  }

  public static function checkSoftDeletes(Request $request, $query, $model) {

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
