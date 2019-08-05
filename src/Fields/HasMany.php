<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;
use SertxuDeveloper\Lyra\Lyra;

class HasMany extends Relation {

  protected $component = "has-many-field";
  protected $resource;
  protected $path;

  protected $hideOnIndex = true;
  protected $hideOnCreate = true;
  protected $hideOnEdit = true;

  public function setResource($resource) {
    $this->resource = $resource;
    $resources = Lyra::getResources();
    $this->path = array_search($resource, $resources);
    return $this;
  }

  protected function retrieveValue($model) {
    $query = $model->{$this->column}();

    if (request()->get('search')) {
      $search = urldecode(request()->get('search'));
      $resource = $this->resource;

      $query = $query->where(function (Builder $query) use ($resource, $search) {
        foreach ($resource::$search as $key => $column) {
          $query = $query->orWhere($column, 'like', "%$search%");
        }
      });

    }

    if (request()->get('sortCol') && request()->get('sortDir')) {
      $sortCol = explode(',', request()->get('sortCol'));
      $sortDir = explode(',', request()->get('sortDir'));
      foreach ($sortCol as $key => $value) {
        $query = $query->orderBy($sortCol[$key], $sortDir[$key]);
      }
    }

    $this->foreign_column = $query->getForeignKeyName();

    $query = DatatypesController::checkSoftDeletes(request(), $query, $model);
    $query = request()->has('perPage') ? $query->paginate(request()->get('perPage')) : $query->paginate(25);

    $resourceCollection = new $this->resource($query);

    $resourceCollection->labels['singular'] = Str::singular($this->name);
    $resourceCollection->labels['plural'] = Str::plural($this->name);

    return $resourceCollection->getCollection(request(), 'index');
  }

  public function get() {
    return [
      "component" => $this->component,
      "name" => $this->name,
      "path" => $this->path,
      "foreign_column" => $this->foreign_column,
      "value" => $this->value,
    ];
  }

}
