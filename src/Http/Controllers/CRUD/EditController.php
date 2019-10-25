<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
use SertxuDeveloper\Lyra\Lyra;

class EditController extends DatatypesController {

  public function edit(Request $request, string $resource, string $id) {
    /** Check if the user has permission to edit the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('edit_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $element = $model::find($id)->get();
    if (!Arr::first($element)) return abort(404, "No query results for model [$model]");
    $resourceCollection = new $resourcesNamespace($element);
    return $resourceCollection->getCollection($request, 'edit');
  }

  public function update(Request $request, string $resource, string $id) {
    /** Check if the user has permission to create (update) the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('create_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $resource = $resourcesNamespace::$model::find($id)->first();

    $collection = $request->post('collection');
    $collection = json_decode($collection, true);
    $values = collect($collection);

    $fields = $resourcesNamespace::getFields($resource);

    $fields = $fields->filter(function ($field) {
      $permission = $field->getPermissions();
      return !$permission['hideOnEdit'];
    })->values();

    $fields->each(function ($field, $key) use ($values, $resource) {
      if ($this->isTranslatorUsable() && $field->data->get('translatable')) {
        $this->updateTranslation($values, $resource);
      } else {
        $field->saveValue($values[$key], $resource);
      }
    });

    $resource->saveOrFail();
  }
}
