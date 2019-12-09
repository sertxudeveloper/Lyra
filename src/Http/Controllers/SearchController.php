<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Facades\Lyra;

class SearchController extends Controller {

  public function search(Request $request) {
    /** Get the Lyra resource from the global array */
    $resources = Lyra::getResources();

    $response = [];
    foreach ($resources as $key => $resource) {
      $model = $resource::$model;
      $results = $resource::search($request->query('q'));

      if (count($results)) {
        $response[] = [
          "name" => (new $resource(new $model))->getLabels()['plural'],
          "key" => $key,
          "results" => collect((new $resource($results))->getCollection($request, 'search')['collection']['data'])
            ->take($resource::$limitResults)->toArray(),
        ];
      }
    }

    return $response;
  }

}
