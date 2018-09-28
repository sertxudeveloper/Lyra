<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use SertxuDeveloper\Lyra\Models\Datatype;

class DatatypesController extends CrudController {

  public function index(Request $request) {
//    dd($request->query('select'));
    $slug = explode('.', $request->route()->getName())[1];

//    $datatype = Datatype::where('slug', $slug)->first();

    $datatype = config('lyra.datatypes.' . $slug);

    $model = $datatype['model'];

    $collection = $model::all();

    if(!auth()->user()->hasPermission('read_' . $slug)) abort(403);


    return view('lyra::datatypes.index', [
      "datatype" => $datatype,
      "collection" => $collection
    ]);
  }

  public function show($id) {
    return view('lyra::datatypes.show', [
      "element"
    ]);
  }
}