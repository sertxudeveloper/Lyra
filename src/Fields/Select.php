<?php

namespace SertxuDeveloper\Lyra\Fields;

class Select extends Field {

  protected $component = "select-field";

  public function options(array $options) {
    $this->data->put('options', $options);
    return $this;
  }

}
