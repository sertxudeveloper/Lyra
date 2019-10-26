<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;

class DatatypesController extends Controller {

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
