<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

abstract class Resource extends JsonResource {

  /** @var Model $model */
  static public string $model;
  static public string $icon = '';
  static public int $priority = 99;
  static public array $perPageOptions = [10, 50, 100];
  static public string $orderBy = 'asc'; // 'desc' or 'asc'

  /** @var string[] $search Columns where the search is enabled */
  static public array $search = [];

  public static function getKeyName(): string {
    return (new static::$model)->getKeyName();
  }

  /**
   * Get the label of the resource
   *
   * @return string
   */
  static public function label(): string {
    return Str::title(Str::snake(class_basename(get_called_class()), ' '));
  }

  /**
   * Create a new instance of the provided model
   *
   * @return mixed
   */
  public static function newModel() {
    $model = static::$model;
    return new $model;
  }

  /**
   * Get the singular label of the resource
   *
   * @return string
   */
  static public function singular(): string {
    return Str::singular(static::label());
  }

  /**
   * Get the slug of the resource
   *
   * @return string
   */
  static public function slug(): string {
    return Str::kebab(class_basename(get_called_class()));
  }

  /**
   * Add the requested sorting method to the query
   *
   * @param Request $request
   * @param Builder $query
   * @return Builder
   */
  static public function sortResource(Request $request, Builder $query): Builder {
    $sortBy = explode(',', $request->input('sortBy'));
    $sortOrder = explode(',', $request->input('sortOrder'));

    /** Do not apply the sorting due to invalid params */
    if (count($sortBy) !== count($sortOrder)) return $query;

    $sort = collect($sortBy)->combine($sortOrder)->toArray();

    foreach ($sort as $column => $direction) {
      if ($direction !== 'asc' && $direction !== 'desc') continue;
      $query->orderBy($column, $direction);
    }

    return $query;
  }

  static public function searchInResource(Request $request, Builder $query): Builder {
    $searchTerm = $request->input('q');

    $query->where(function (Builder $query) use ($searchTerm) {
      foreach (static::$search as $column) {
        $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
      }
    });

    return $query;
  }

  /**
   * The cards' resource definition
   * @return array
   */
  abstract public function cards(): array;

  /**
   * The fields' resource definition
   *
   * @return array
   */
  abstract public function fields(): array;

  /**
   * The actions' resource definition
   *
   * @return array
   */
  abstract public function actions(): array;

  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {

    $fields = [];
    foreach ($this->fields() as $field) {
      if (!$field->canShow($request)) continue;
      $fields[] = $field->toArray($this->resource);
    }

    $response = [
      'key' => $this->resource->getKey(),
      'trashed' => (method_exists($this->resource, 'trashed')) ? $this->resource->trashed() : false,
      'fields' => $fields,
    ];

    $route = str_replace(config('lyra.routes.api.name'), '', $request->route()->getName());
    if ($route === 'resources.edit' || $route === 'resources.update') {
      $updated_at = (new $this->resource)->getUpdatedAtColumn();
      $response['updated_at'] = $this->resource->$updated_at;
    }

    return $response;
  }

  /**
   * Validate the update request received.
   *
   * @param Request $request
   * @return array
   * @throws ValidationException
   */
  public function validateCreation(Request $request): array {
    $validator = Validator::make($request->all(), $this->formatRules($request));
    return $validator->validate();
  }

  /**
   * Validate the update request received.
   *
   * @param Request $request
   * @return array
   * @throws ValidationException
   */
  public function validateUpdating(Request $request): array {
    $validator = Validator::make($request->all(), $this->formatRules($request));
    return $validator->validate();
  }

  /**
   * Format the rules for the validation
   *
   * @param Request $request
   * @return array
   */
  protected function formatRules(Request $request): array {
    $rules = [];
    $route = str_replace(config('lyra.routes.api.name'), '', $request->route()->getName());
    $isUpdating = $route == 'resources.update';

    foreach ($this->fields() as $field) {
      if (!$field->canShow($request)) continue;
      $fieldRules = $isUpdating ? $field->updatingRules : $field->creationRules;
      $rules[$field->column] = str_replace('{{resourceId}}', $this->resource->getKey(), $fieldRules);
    }

    return $rules;
  }
}
