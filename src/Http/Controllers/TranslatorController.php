<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use SertxuDeveloper\Lyra\Lyra;

class TranslatorController extends Controller {

  public static function isTranslatorUsable() {
    return config('lyra.translator.enabled') && request()->get('lang') &&
      request()->get('lang') !== config('lyra.translator.default_locale');
  }

  public static function removeTranslation($lang, $element) {
    DB::table($element->getTable() . '_translations')
      ->where([['locale', $lang], ['id', $element[$element->getKeyName()]]])->delete();
  }

  public static function removeTranslations($element) {
    DB::table($element->getTable() . '_translations')
      ->where('id', $element[$element->getKeyName()])->delete();
  }

  public static function updateTranslation($fields, $resource) {
    $translation = DB::table($resource->getTable() . config('lyra.translator.table_suffix'))
      ->where([['locale', request()->get('lang')], ['id', $resource[$resource->getKeyName()]]])->first();

    if ($translation) {

      $data = ['updated_at' => new Carbon()];
      $data = array_merge($data, $fields);

      DB::table($resource->getTable() . config('lyra.translator.table_suffix'))
        ->where([
          [config('lyra.translator.locale_column'), request()->get('lang')],
          ['id', $resource->getKeyName()]
        ])->update($data);
    } else {

      $data = [
        'id' => $resource->getKey(),
        'locale' => request()->get('lang'),
        'created_at' => new Carbon(),
        'updated_at' => new Carbon()
      ];

      $data = array_merge($data, $fields);
      DB::table($resource->getTable() . '_translations')->insert($data);
    }
  }

}
