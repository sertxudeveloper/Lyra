<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Lyra;

abstract class Resource extends ResourceCollection {

  private $type;
  public static $model;
  public static $search = [];
  public static $title = '';
  public static $subtitle = '';
  public static $limitResults;
  public $singular;
  public $plural;
  public static $perPageOptions = [15, 50, 100];

  private $response = [];

  public abstract function fields();

  public static function getFields($resource) {
    return collect((new static($resource))->fields());
  }

  public function getLabels() {
    $singular = ($this->singular) ? $this->singular : Str::singular(class_basename($this));
    $plural = ($this->plural) ? $this->plural : Str::plural(class_basename($this));

    return ['singular' => $singular, 'plural' => $plural];
  }

  protected function getAvailableLanguages() {
    $available_locales = config('lyra.translator.available_locales');
    $default_locale = config('lyra.translator.default_locale');
    array_unshift($available_locales, $default_locale);
    return $available_locales;
  }

  public function getCollection(Request $request, string $type) {
    $this->type = $type;

    $labels = $this->getLabels();
    $this->response['labels'] = $labels;
    $this->response['collection'] = $this->toArray($request);
    $this->response['perPageOptions'] = $this::$perPageOptions;

    if (config('lyra.translator.enabled') && $this->isTranslatable()) {
      $this->response['languages'] = $this->getAvailableLanguages();
    }

    return $this->response;
  }

  public function toArray($request) {
    $resource = ($this->type !== 'create') ? $this->resource->toArray() : [];
    $this->collection = ($this->type !== 'create') ? $this->collection : collect([new static::$model]);

    if (!Arr::first($resource) || $this->type !== 'index') $resource = [];
    $resource = (object)$resource;

    $resource->data = $this->collection->map(function ($model) use ($request) {
      $fields = [];

      foreach ($this->fields() as $field) {

        if ($this->type === 'search') {
          if ($field->isPrimary()) {
            $fields['primary'] = $field->getValue($model, 'index')['value'];
          } else {
            if ($field->getColumnName() === static::$title) {
              $fields['title'] = $field->getValue($model, 'index')['value'];
            } else if ($field->getColumnName() === static::$subtitle) {
              $fields['subtitle'] = $field->getValue($model, 'index')['value'];
            } else {
              continue;
            }
          }
        } else {
          $permission = $field->getPermissions();
          if ($permission['hideOn' . ucfirst($this->type)]) continue;
          if ($field->isPrimary() && $this->hasSoftDeletes()) {
            $field = $field->getValue($model, $this->type);
            $field['trashed'] = $model->trashed();
            $fields[] = $field;
          } else {
            $fields[] = $field->getValue($model, $this->type);
          }
        }

      }

      return $fields;
    });

    if ($this->type === 'edit') $resource->preventConflict = $this->collection[0][static::$model::UPDATED_AT];

    $resource->hasSoftDeletes = $this->hasSoftDeletes();

    $resource->permissions = [
      "read" => Lyra::checkPermission('read', $request->route('resource')),
      "edit" => Lyra::checkPermission('edit', $request->route('resource')),
      "delete" => Lyra::checkPermission('delete', $request->route('resource')),
    ];

    $resource = collect($resource)->filter(function ($item, $key) {
      return !preg_match('/^[0-9]+$/', $key);
    })->toArray();

    return $resource;
  }

  public static function hasSoftDeletes() {
    return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(static::$model));
  }

  public static function isTranslatable() {
    return in_array('SertxuDeveloper\Translatable\Traits\HasTranslations', class_uses(static::$model));
  }

  static public function search($term) {
    if (!count(static::$search)) return [];

    $query = static::$model::query();

    foreach (static::$search as $key) {
      $query->orWhere($key, 'LIKE', "%{$term}%");
    }

    return $query->get();
  }
}
