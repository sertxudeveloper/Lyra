<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
use SertxuDeveloper\Lyra\Http\Controllers\TranslatorController;
use SertxuDeveloper\Lyra\Lyra;

class EditController extends DatatypesController {

  /**
   * Get the Lyra resource edit form for the specific resource and model instance.
   *
   * @param Request $request
   * @param string $resource Resource slug
   * @param string $id Model id
   *
   * @return array
   */
  public function edit(Request $request, string $resource, string $id) {
    /** Check if the user has permission to edit the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('write_' . $resource)) abort(403);

    /** Get the Lyra resource from the global array and search the model instance in the database */
    $resourcesNamespace = Lyra::getResources()[$resource];
    $modelClass = $resourcesNamespace::$model;
    $model = $modelClass::find($id);

    /** Check if the model exists, if not trow a 404 error code */
    if (!Arr::first($model)) return abort(404, "No query results for model [$modelClass]");

    /** Create a new collection with the model instance in the new Lyra resource instance */
    $resourceCollection = new $resourcesNamespace(collect([$model]));

    /** Return the Lyra resource collection with type 'create' as an array */
    return $resourceCollection->getCollection($request, 'edit');
  }

  /**
   * Search for the model instance in the database, update it and store it in the database.
   *
   * @param Request $request
   * @param string $resource Resource slug
   * @param string $id Model id
   *
   * @return void
   */
  public function update(Request $request, string $resource, string $id) {
    /** Check if the user has permission to create (update) the $resource requested */
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('write_' . $resource)) abort(403);

    /** Get the Lyra resource from the global array and create a new model instance */
    $resourcesNamespace = Lyra::getResources()[$resource];
    $resource = $resourcesNamespace::$model::find($id);

    /** Get the data from the request and prepare it to be processed */
    $collection = $request->post('collection');
    $collection = json_decode($collection, true);
    $values = collect($collection);

    /** Get the Lyra resource fields to be able to call the method 'saveValue' and 'syncRelationship' at each field */
    $fields = $resourcesNamespace::getFields($resource);

    /** Filter the fields which should not be included in the database because were hidden or is the primary key */
    $fields = $fields->filter(function ($field) {
      $permission = $field->getPermissions();
      return !$permission['hideOnEdit'];
    })->values();

    /** Process first the common fields with a column in the database */
    $fields->each(function ($field, $key) use ($values, $resource) {
      if (TranslatorController::isTranslatorUsable() && $field->isTranslatable()) {
        TranslatorController::updateTranslation($values, $resource);
      } else {
        if (method_exists($field, 'saveValue')) $field->saveValue($values[$key], $resource);
      }
    });

    /** Save the model to get the $id */
    $resource->saveOrFail();

    /** Process the relationships fields and create it with the key $id obtained previously */
    $fields->each(function ($field, $key) use ($values, $resource) {
      if (method_exists($field, 'syncRelationship')) $field->syncRelationship($values[$key], $resource);
    });

    return null;
  }
}
