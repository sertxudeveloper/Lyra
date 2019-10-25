<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Str;

class Id extends Field {

  protected $component = "id-field";
  protected $hideOnCreate = true;

  public static function make($name, $column = null) {
    $class = new static();
    $class->data = collect([]);

    if (!$column) {
      $column = Str::snake($name);
    } else {
      if (is_callable($column)) {
        $class->callback = $column;
        $column = Str::snake($name);
        $class->hideOnCreate = true;
        $class->hideOnEdit = true;
      }
    }

    $class->data->put('component', $class->component);
    $class->data->put('name', $name);
    $class->data->put('column', $column);
    $class->data->put('primary', true);
    return $class;
  }

}
