<?php

namespace SertxuDeveloper\Lyra\Fields;

class Password extends Field {

  protected $component = "password-field";

  protected $hideOnIndex = true;
  protected $hideOnShow = true;

  protected static function getNewInstance() {
    return new self();
  }
}