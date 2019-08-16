<?php

namespace SertxuDeveloper\Lyra\Fields;

class Text extends Field {

  protected $component = "text-field";
  protected $size = false;

  public function size($number = null) {
    $this->size = $number;
    return $this;
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => $this->name,
      "column" => $this->column,
      "description" => $this->description,
      "sortable" => $this->sortable,
      "primary" => $this->primary,
      "size" => $this->size,
      "value" => $this->value,
    ];
  }

}
