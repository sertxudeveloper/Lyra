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
    DB::table($element->getTable() . config('lyra.translator.table_suffix'))
      ->where([['locale', $lang], [$element->getKeyName(), $element->getKey()]])->delete();
  }

  public static function removeTranslations($element) {
    DB::table($element->getTable() . config('lyra.translator.table_suffix'))
      ->where($element->getKeyName(), $element->getKey())->delete();
  }

  public static function updateTranslation($fields, $resource) {
    $translation = DB::table($resource->getTable() . config('lyra.translator.table_suffix'))
      ->where([['locale', request()->get('lang')], [$resource->getKeyName(), $resource->getKey()]])->first();

    if ($translation) {

      $data = ['updated_at' => new Carbon()];
      $data = array_merge($data, $fields);

      DB::table($resource->getTable() . config('lyra.translator.table_suffix'))
        ->where([
          [config('lyra.translator.locale_column'), request()->get('lang')],
          [$resource->getKeyName(), $resource->getKey()]
        ])->update($data);
    } else {

      $data = [
        $resource->getKeyName() => $resource->getKey(),
        'locale' => request()->get('lang'),
        'created_at' => new Carbon(),
        'updated_at' => new Carbon()
      ];

      $data = array_merge($data, $fields);
      DB::table($resource->getTable() . config('lyra.translator.table_suffix'))->insert($data);
    }
  }

}
