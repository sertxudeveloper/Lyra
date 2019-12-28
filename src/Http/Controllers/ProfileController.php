<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SertxuDeveloper\Lyra\Facades\Lyra;

class ProfileController extends Controller {

  public function edit(Request $request) {
    /** Get the Lyra resource from the global array and search the model instance in the database */
    $resourcesNamespace = config('lyra.profile_resource');
    $modelClass = $resourcesNamespace::$model;
    $model = $modelClass::find(Lyra::auth()->user()->id);

    /** Check if the model exists, if not trow a 404 error code */
    if (!Arr::first($model)) return abort(404, "No query results for model [$modelClass]");

    /** Create a new collection with the model instance in the new Lyra resource instance */
    $resourceCollection = new $resourcesNamespace(collect([$model]));

    /** Return the Lyra resource collection with type 'create' as an array */
    return $resourceCollection->getCollection($request, 'edit');
  }

  public function update(Request $request) {
    /** Get the Lyra resource from the global array and create a new model instance */
    $resourcesNamespace = config('lyra.profile_resource');
    $resource = $resourcesNamespace::$model::find(Lyra::auth()->user()->id);

    /** Get the data from the request and prepare it to be processed */
    $collection = $request->post('collection');
    $collection = json_decode($collection, true);
    $values = collect($collection);

    /** Check if the model has been modified while editing */
    $preventConflict = $request->post('preventConflict');
    if ($preventConflict !== $resource->updated_at->toJSON()) abort(409);

    /** Get the Lyra resource fields to be able to call the method 'saveValue' and 'syncRelationship' at each field */
    $fields = $resourcesNamespace::getFields($resource);

    /** Filter the fields which should not be included in the database because were hidden or is the primary key */
    $fields = $fields->filter(function ($field) {
      $permission = $field->getPermissions();
      return !$permission['hideOnEdit'];
    })->values();

    $errors = new \Illuminate\Support\MessageBag;

    /** Process first the common fields with a column in the database */
    $fields->each(function ($field, $key) use ($values, $resource, &$errors) {
      if (method_exists($field, 'validate')) {
        $validation = $field->validate($values[$key]['value'], $resource);
        if ($validation->fails()) $errors->merge($validation->errors()->toArray());
      }

      if (TranslatorController::isTranslatorUsable() && $field->isTranslatable()) {
        TranslatorController::updateTranslation($values[$key], $resource);
      } else {
        if (method_exists($field, 'saveValue')) $field->saveValue($values[$key], $resource);
      }
    });

    if ($errors->isEmpty()) {
      /** Save the model to get the $id */
      $resource->saveOrFail();

      /** Process the relationships fields and create it with the key $id obtained previously */
      $fields->each(function ($field, $key) use ($values, $resource) {
        if (method_exists($field, 'syncRelationship')) $field->syncRelationship($values[$key], $resource);
      });
    } else {
      abort(400, $errors->toJson());
    }

    return null;
  }
}
