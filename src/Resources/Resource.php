<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Resource extends ResourceCollection {

  static public string $model = '';
  static public string $icon = '';
  static public int $priority = 99;
  static public array $perPageOptions = [10, 50, 100];
  static public string $orderBy = 'asc'; // 'desc' or 'asc'

  /**
   * Get the slug of the resource
   *
   * @return string
   */
  static public function slug(): string {
    return Str::kebab(class_basename(get_called_class()));
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
   * Get the singular label of the resource
   *
   * @return string
   */
  static public function singular(): string {
    return Str::singular(static::label());
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
   * The fields' resource definition
   *
   * @return array
   */
  abstract public function fields(): array;

  /**
   * The cards' resource definition
   * @return array
   */
  abstract public function cards(): array;

  /**
   * Transform the resource into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    $this->collection = $this->collection->map(function ($model) use ($request) {
      $items = [];

      $fields = [];
      foreach ($this->fields() as $field) {
        if (!$field->canShow($request)) continue;
        $fields[] = $field->toArray($model);
      }

      $items['key'] = $model->getKey();
      $items['trashed'] = (method_exists($model, 'trashed')) ? $model->trashed() : false;
      $items['fields'] = $fields;

      return $items;
    });

    return [
      'data' => $this->collection,
      'labels' => [
        'singular' => $this::singular(),
        'plural' => $this::label(),
      ],
      'perPageOptions' => $this::$perPageOptions,
    ];
  }

  /**
   * Create a paginate-aware HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  protected function preparePaginatedResponse($request)
  {
    if ($this->preserveAllQueryParameters) {
      $this->resource->appends($request->query());
    } elseif (! is_null($this->queryParameters)) {
      $this->resource->appends($this->queryParameters);
    }

    return (new Pagination($this))->toResponse($request);
  }
}
