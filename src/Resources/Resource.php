<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Resource extends ResourceCollection {

  static public string $model = '';
  static public string $icon = '';
  static public int $priority = 99;
  static public array $perPageOptions = [15, 50, 100];

  /**
   * Get the slug of the resource
   *
   * @return string
   */
  static public function slug(): string {
    return Str::kebab(static::label());
  }

  /**
   * Get the label of the resource
   *
   * @return string
   */
  static public function label(): string {
    return class_basename(get_called_class());
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
    $this->collection = $this->collection->map(function ($model) {
      $items = [];

      $fields = [];
      foreach ($this->fields() as $field) {
        $fields[] = [
          'component' => $field->component,
          'column' => $field->column,
          'value' => $model->{$field->column},
        ];
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
}
