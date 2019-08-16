<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Resource extends ResourceCollection {

  protected $type;
  public static $model;
  public static $primary;
  public $singular;
  public $plural;

  public abstract function fields();

  public static function getPrimary() {
    return (new static::$model)->getKeyName();
  }

  public static function getFields($resource) {
    return collect((new static($resource))->fields());
  }

  public function getCollection(Request $request, string $type) {
    $this->type = $type;

    $singular = ($this->singular) ? $this->singular : Str::singular(class_basename($this));
    $plural = ($this->plural) ? $this->plural : Str::plural(class_basename($this));

    return [
      "labels" => [
        "singular" => $singular,
        "plural" => $plural
      ],
      "collection" => $this->toArray($request)
    ];
  }

  public function toArray($request) {
    $resource = ($this->type !== 'create') ? $this->resource->toArray() : [];
    $this->collection = ($this->type !== 'create') ? $this->collection : collect([[]]);


    if (!Arr::first($resource) || $this->type !== 'index') $resource = [];
    $resource = (object)$resource;

    $resource->data = $this->collection->map(function ($item) use ($request) {
      if (isset($item->load)) $item->load($this->with);
      $fields = [];

      foreach ($this->fields() as $field) {
        $permission = $field->getPermissions();
        if ($permission['hideOn' . ucfirst($this->type)]) continue;

        $field = $field->getValue($item, $this->type);

        $fields[] = $field;
      }

      return $fields;
    });

    $resource->hasSoftDeletes = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(static::$model));

    $resource = collect($resource)->filter(function ($item, $key) {
      return !preg_match('/^[0-9]$/', $key);
    })->toArray();

    return $resource;
  }
}
