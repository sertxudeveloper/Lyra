<?php

namespace SertxuDeveloper\Lyra\Fields;

class Url extends Field {

  protected $component = "url-field";

  public function size($number = null) {
    $this->data->put('size', $number);
    return $this;
  }
}
