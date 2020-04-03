<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Http\Controllers\DatatypesController;

class HasMany extends Relation {

  protected $component = "has-many-field";

  protected $hideOnIndex = true;
  protected $hideOnCreate = true;
  protected $hideOnEdit = true;

  /**
   * Get the translated value of the Field
   * The language is specified as a request GET input
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getTranslatedValue($model, string $type) {
    return abort(500, "This field currently doesn't support translations");
  }

  /**
   * Get the original value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    $query = $model->{$this->data->get('column')}();

    /** Search functionality */
    if (request()->get('search')) {
      $search = urldecode(request()->get('search'));
      $resource = $this->data->get('resource');

      $query = $query->where(function (Builder $query) use ($resource, $search) {
        foreach ($resource::$search as $key => $column) {
          $query = $query->orWhere($column, 'like', "%$search%");
        }
      });

    }

    /** Column sort functionality */
    if (request()->get('sortCol') && request()->get('sortDir')) {
      $query->getBaseQuery()->orders = null;
      $sortCol = explode(',', request()->get('sortCol'));
      $sortDir = explode(',', request()->get('sortDir'));
      foreach ($sortCol as $key => $value) {
        $query = $query->orderBy($sortCol[$key], $sortDir[$key]);
      }
    }

    $this->data->put('foreign_column', $query->getForeignKeyName());
    $resource = $this->data->get('resource');

    $query = DatatypesController::checkSoftDeletes(request(), $query, $model->{$this->data->get('column')}()->getRelated());
    $query = request()->has('perPage') ? $query->paginate(request()->get('perPage')) : $query->paginate($resource::$perPageOptions[0]);

    $resourceCollection = new $resource($query);

    $resourceCollection->singular = Str::singular($this->data->get('name'));
    $resourceCollection->plural = Str::plural($this->data->get('name'));

    return $resourceCollection->getCollection(request(), 'index');
  }
}
