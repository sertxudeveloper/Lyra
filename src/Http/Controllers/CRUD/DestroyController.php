<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
use SertxuDeveloper\Lyra\Http\Controllers\TranslatorController;
use SertxuDeveloper\Lyra\Lyra;

class DestroyController extends DatatypesController {

  /**
   * Delete the requested model or mark as deleted if it has softDeletes enabled.
   * If the model has translations and no softDeletes there will be deleted too.
   * If the $request has the key 'lang' setted will only remove that translation not all the model.
   *
   * @param Request $request
   * @param string $resource Resource slug
   * @param string $id Model id
   *
   * @return HttpResponseException | null
   */
  public function delete(Request $request, string $resource, string $id) {
    /** Check if the user has permission to delete the $resource requested */
    if (!Lyra::checkPermission('delete', $resource)) abort(403);

    /** Get the Lyra resource from the global array */
    $resourcesNamespace = Lyra::getResources()[$resource];

    /** Get the first model instance with the provided $id */
    $model = $resourcesNamespace::$model::find($id);

    /** If no model where found, return a HTTP 404 code error */
    if (!$model) return abort(404);

    if (TranslatorController::isTranslatorUsable()) {
      TranslatorController::removeTranslation($request->get('lang'), $model);
    } else {
      if ($resourcesNamespace::isTranslatable() && !$resourcesNamespace::hasSoftDeletes()) {
        TranslatorController::removeTranslations($model);
      }

      $fields = $resourcesNamespace::getFields($model);

      if (!$resourcesNamespace::hasSoftDeletes()) {
        $fields->each(function ($field, $key) use ($model) {
          if (method_exists($field, 'delete')) {
            $field->delete($model);
          }
        });
      }

      $model->delete();
    }

    return null;
  }

  /**
   * Restore the requested model if it's trashed, if not will throw a 404 error.
   *
   * @param Request $request
   * @param string $resource Resource slug
   * @param string $id Model id
   *
   * @return HttpResponseException | null
   */
  public function restore(Request $request, string $resource, string $id) {
    /** Check if the user has permission to delete (recover) the $resource requested */
    if (!Lyra::checkPermission('delete', $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model::onlyTrashed()->find($id);
    if (!$model) return abort(404);
    $model->restore();
    return null;
  }

  /**
   * Force delete the requested model.
   * If the model is translatable all its translations will be deleted too.
   *
   * @param Request $request
   * @param string $resource Resource slug
   * @param string $id Model id
   *
   * @return HttpResponseException | null
   */
  public function forceDelete(Request $request, string $resource, string $id) {
    /** Check if the user has permission to delete the $resource requested */
    if (!Lyra::checkPermission('delete', $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model::withTrashed()->find($id);
    if (!$model) return abort(404);
    if (config('lyra.translator.enabled') && $resourcesNamespace::isTranslatable()) {
      $this->removeTranslations($model);
    }
    $model->forceDelete();
    return null;
  }
}
