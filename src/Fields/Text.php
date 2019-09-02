<?php

namespace SertxuDeveloper\Lyra\Fields;

class Text extends Field {

  protected $component = "text-field";

  public function size($number = null) {
    $this->data->put('size', $number);
    return $this;
  }
}
