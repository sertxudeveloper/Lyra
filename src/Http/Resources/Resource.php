<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use Illuminate\Container\Container;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Resource extends ResourceCollection {

  protected $type;
  public static $model;
  public static $primary;
  public $labels;

  public abstract function fields();

  public function getCollection(string $type) {
    $this->type = $type;

    return [
      "labels" => $this->labels,
      "collection" => $this->toArray(Container::getInstance()->make('request'))
    ];
  }

  public function toArray($request) {
    $resource = ($this->type !== 'create') ? $this->resource->toArray() : [];
    $this->collection = ($this->type !== 'create') ? $this->collection : collect([[]]);

    if (isset($resource[0])) $resource = [];
    $resource = (object)$resource;

    $resource->data = $this->collection->map(function ($item) {
      if (isset($item->load)) $item->load($this->with);
      $fields = [];

      foreach ($this->fields() as $field) {
        if ($field['hideOn' . ucfirst($this->type)]) continue;

        if (isset($field['read']) && isset($field['write']) && isset($field['class'])) {
          $field['read']['value'] = $item[$field['column']][$field['read']['key']];
          $field['write']['value'] = $item[$field['column']][$field['write']['key']];

          if (isset($field['class']['where'])) {
            $field['class']['value'] = $field['class']['key']::where($field['class']['where'])->get();
          } else {
            $field['class']['value'] = $field['class']['key']::all();
          }
        } else {
          $field['value'] = isset($item[$field['column']]) ? $item[$field['column']] : null;
        }

        if ($field['primary'] === true) {
          if (isset($item[$item->getDeletedAtColumn()])) {
            $field['softDeleted'] = $item[$item->getDeletedAtColumn()];
          }
        }

        $fields[] = $field;
      }

      return $fields;
    });

    return $resource;
  }
}
