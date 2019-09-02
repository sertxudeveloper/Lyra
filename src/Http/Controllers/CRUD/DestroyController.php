<?php

namespace SertxuDeveloper\Lyra\Http\Controllers\CRUD;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
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
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('delete_' . $resource)) abort(403);

    /** Get the Lyra resource from the global array */
    $resourcesNamespace = Lyra::getResources()[$resource];

    /** Get the first model instance with the provided $id */
    $model = $resourcesNamespace::$model;
    $element = $model::where($resourcesNamespace::getPrimary(), $id)->first();

    /** If no model where found, return a HTTP 404 code error */
    if (!$element) return abort(404);

    if ($this->isTranslatorUsable()) {
      $this->removeTranslation($request->get('lang'), $element);
    } else {
      if ($resourcesNamespace::isTranslatable() && !$resourcesNamespace::hasSoftDeletes()) {
        $this->removeTranslations($element);
      }
      $element->delete();
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
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('delete_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $element = $model::where($resourcesNamespace::getPrimary(), $id)->onlyTrashed()->first();
    if (!$element) return abort(404);
    $element->restore();
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
    if (config('lyra.authenticator') == 'lyra') if (!Lyra::auth()->user()->hasPermission('delete_' . $resource)) abort(403);

    $resourcesNamespace = Lyra::getResources()[$resource];
    $model = $resourcesNamespace::$model;
    $element = $model::where($resourcesNamespace::getPrimary(), $id)->first();
    if (!$element) return abort(404);
    if (config('lyra.translator.enabled') && $resourcesNamespace::isTranslatable()) {
      $this->removeTranslations($element);
    }
    $element->forceDelete();
    return null;
  }
}
