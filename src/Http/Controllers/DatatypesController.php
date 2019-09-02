<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use SertxuDeveloper\Lyra\Lyra;

class DatatypesController extends Controller {


  /**
   * @deprecated
   * @return bool
   */
  protected function isTranslatorUsable() {
    return config('lyra.translator.enabled') && request()->get('lang') &&
      request()->get('lang') !== config('lyra.translator.default_locale');
  }

  /**
   * @deprecated
   * @param $values
   * @param $resource
   * @throws \Exception
   */
  protected function updateTranslation($values, $resource) {
    $fields = $values->filter(function ($value) {
      return isset($value['translatable']);
    })->mapWithKeys(function ($field) {
      return [$field['column'] => $field['value']];
    })->toArray();

    $translation = DB::table($resource->getTable() . '_translations')
      ->where([['locale', request()->get('lang')], ['id', $resource[$resource->getKeyName()]]])->first();
    if ($translation) {

      $data = ['updated_at' => new Carbon()];
      $data = array_merge($data, $fields);

      DB::table($resource->getTable() . '_translations')
        ->where([['locale', request()->get('lang')], ['id', $resource[$resource->getKeyName()]]])
        ->update($data);
    } else {

      $data = [
        'id' => $resource[$resource->getKeyName()],
        'locale' => request()->get('lang'),
        'created_at' => new Carbon(),
        'updated_at' => new Carbon()
      ];

      $data = array_merge($data, $fields);
      DB::table($resource->getTable() . '_translations')->insert($data);
    }
  }

  /**
   * @deprecated
   * @param $lang
   * @param $element
   */
  protected function removeTranslation($lang, $element) {
    DB::table($element->getTable() . '_translations')
      ->where([['locale', $lang], ['id', $element[$element->getKeyName()]]])->delete();
  }

  /**
   * @deprecated
   * @param $element
   */
  protected function removeTranslations($element) {
    DB::table($element->getTable() . '_translations')
      ->where('id', $element[$element->getKeyName()])->delete();
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
