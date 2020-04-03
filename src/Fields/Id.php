<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Str;

class Id extends Field {

  protected $component = "id-field";
  protected $hideOnCreate = true;

  /**
   * Create a new instance of the Field
   *
   * @param string $name
   * @param null $column
   * @return self
   */
  public static function make(string $name, $column = null) {
    $class = new static();
    $class->data = collect([]);

    if (!$column) $column = Str::snake($name);

    $class->data->put('component', $class->component);
    $class->data->put('name', $name);
    $class->data->put('column', $column);
    $class->data->put('primary', true);
    return $class;
  }

}
