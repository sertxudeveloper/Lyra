<?php

namespace SertxuDeveloper\Lyra\Fields;

class Select extends Field {

  protected $component = "select-field";
//  protected $options = ["ACTIVE", "BLOCKED"];

  public function options(array $options) {
    $this->options = $options;
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
      "options" => $this->options,
      "value" => $this->value,
    ];
  }
}
