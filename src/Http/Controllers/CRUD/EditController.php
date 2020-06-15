<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\MessageBag;
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
    if (!Lyra::checkPermission('write', $resource)) abort(403);

    if(config('lyra.translator.enabled') && $request->has('lang')) App::setLocale($request->get('lang'));

    /** Get the Lyra resource from the global array and search the model instance in the database */
    $resourcesNamespace = Lyra::getResources()[$resource];
    $modelClass = $resourcesNamespace::$model;
    $model = $modelClass::find($id);

    /** Check if the model exists, if not trow a 404 error code */
    if (!$model) return abort(404, "No query results for model [$modelClass]");

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
    if (!Lyra::checkPermission('write', $resource)) abort(403);

    if(config('lyra.translator.enabled') && $request->has('lang')) App::setLocale($request->get('lang'));

    /** Get the Lyra resource from the global array and create a new model instance */
    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model::find($id);

    /** Get the data from the request and prepare it to be processed */
    $collection = $request->post('collection');
    $collection = json_decode($collection, true);
    $values = collect($collection);

    /** Check if the model has been modified while editing */
    $preventConflict = $request->post('preventConflict');
    if ($preventConflict !== $model[$model::UPDATED_AT]->toJSON()) abort(409);

    /** Get the Lyra resource fields to be able to call the method 'saveValue' and 'syncRelationship' at each field */
    $fields = $resourcesNamespace::getFields($model);

    /** Filter the fields which should not be included in the database because were hidden or is the primary key */
    $fields = $fields->filter(function ($field) {
      $permission = $field->getVisibility();
      return !$permission['hideOnEdit'];
    })->values();

    $errors = new MessageBag;

    $translatableFields = [];

    /** Process first the common fields with a column in the database */
    $fields->each(function ($field, $key) use ($values, $model, &$errors, &$translatableFields) {
      if (method_exists($field, 'validate')) {
        $validation = $field->validate($values[$key]['value'], $model);
        if ($validation->fails()) $errors->merge($validation->errors()->toArray());
      }

      if (TranslatorController::isTranslatorUsable() && $field->isTranslatable()) {
        $translatableFields[$values[$key]['column']] = $values[$key]['value'];
      } else {
        if (method_exists($field, 'saveValue')) $field->saveValue($values[$key], $model);
      }
    });

    if (count($translatableFields)) {
      TranslatorController::updateTranslation($translatableFields, $model);
    }

    if ($errors->isEmpty()) {
      /** Save the model to get the $id */
      $model->saveOrFail();

      /** Process the relationships fields and create it with the key $id obtained previously */
      $fields->each(function ($field, $key) use ($values, $model) {
        if (method_exists($field, 'syncRelationship')) $field->syncRelationship($values[$key], $model);
      });
    } else {
      abort(400, $errors->toJson());
    }

    return null;
  }
}
