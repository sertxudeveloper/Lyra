<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use SertxuDeveloper\Lyra\Facades\Lyra;

class SearchController extends Controller {

  public function search(Request $request) {
    /** Get the Lyra resource from the global array */
    $resources = Lyra::getResources();
    $response = collect();
    $query = $request->query('q');

    if (preg_match('/^resource:[\w]+ [\s\S]*/', $query)) {
      $resourceName = explode('resource:', $query)[1];
      $resourceName = explode(' ', $resourceName)[0];
      $resource = $resources[$resourceName];

      $query = explode(' ', $query);
      array_shift($query);
      $query = implode(' ', $query);

      $response->push($this->searchResource($resource, $query, $resourceName));
    } else {

      foreach ($resources as $key => $resource) {
        $response->push($this->searchResource($resource, $query, $key));
      }
    }

    return $response->filter()->values();
  }

  private function searchResource($resource, $query, $key) {
    $model = $resource::$model;
    $results = $resource::search($query);

    if (count($results) && $resource::$title) {
      return [
        "name" => (new $resource(new $model))->getLabels()['plural'],
        "key" => $key,
        "results" => collect((new $resource($results))->getCollection(request(), 'search')['collection']['data'])
          ->take($resource::$limitResults)->toArray(),
      ];
    }

    return null;
  }

}
