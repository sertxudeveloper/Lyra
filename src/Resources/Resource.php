<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class Resource extends JsonResource {

  static public string $model = '';
  static public string $icon = '';
  static public int $priority = 99;
  static public array $perPageOptions = [10, 50, 100];
  static public string $orderBy = 'asc'; // 'desc' or 'asc'

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
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array|Arrayable|JsonSerializable
   */
  public function toArray($request): array {

    $fields = [];
    foreach ($this->fields() as $field) {
      if (!$field->canShow($request)) continue;
      $fields[] = $field->toArray($this->resource);
    }

    return [
      'key' => $this->resource->getKey(),
      'trashed' => (method_exists($this->resource, 'trashed')) ? $this->resource->trashed() : false,
      'fields' => $fields,
    ];
  }
}
