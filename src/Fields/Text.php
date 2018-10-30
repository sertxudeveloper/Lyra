<?php

namespace SertxuDeveloper\Lyra\Fields;

class Text extends Field {

  protected $component = "text-field";

  protected static function getNewInstance() {
    return new self();
  }
}