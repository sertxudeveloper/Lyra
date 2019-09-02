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

  protected $response = [];

  public abstract function fields();

  public static function getPrimary() {
    return (new static::$model)->getKeyName();
  }

  public static function getFields($resource) {
    return collect((new static($resource))->fields());
  }

  protected function getAvailableLanguages() {
    $available_locales = config('lyra.translator.available_locales');
    $default_locale = config('lyra.translator.default_locale');
    array_unshift($available_locales, $default_locale);
    return $available_locales;
  }

  public function getCollection(Request $request, string $type) {
    $this->type = $type;

    $singular = ($this->singular) ? $this->singular : Str::singular(class_basename($this));
    $plural = ($this->plural) ? $this->plural : Str::plural(class_basename($this));

    $this->response['labels'] = ["singular" => $singular, "plural" => $plural];
    if (config('lyra.translator.enabled')) $this->response['languages'] = $this->getAvailableLanguages();
    $this->response['collection'] = $this->toArray($request);

    return $this->response;
  }

  public function toArray($request) {
    $resource = ($this->type !== 'create') ? $this->resource->toArray() : [];
    $this->collection = ($this->type !== 'create') ? $this->collection : collect([new static::$model]);

    if (!Arr::first($resource) || $this->type !== 'index') $resource = [];
    $resource = (object)$resource;

    $resource->data = $this->collection->map(function ($item) use ($request) {
      $fields = [];

      foreach ($this->fields() as $field) {
        $permission = $field->getPermissions();
        if ($permission['hideOn' . ucfirst($this->type)]) continue;

        $field = $field->getValue($item, $this->type, get_class($this));
        $fields[] = $field;
      }
      return $fields;
    });

    $resource->hasSoftDeletes = $this->hasSoftDeletes();

    $resource = collect($resource)->filter(function ($item, $key) {
      return !preg_match('/^[0-9]$/', $key);
    })->toArray();

    return $resource;
  }

  public static function hasSoftDeletes() {
    return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(static::$model));
  }

  public static function isTranslatable() {
    return in_array('App\Traits\Common\Translatable', class_uses(static::$model));
  }
}
