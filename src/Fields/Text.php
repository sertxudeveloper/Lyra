<?php

namespace SertxuDeveloper\Lyra\Fields;

class Text extends Field {

  protected $component = "text-field";

  public function size(int $number = null, bool $hardMode = false){
    $this->data->put('size', [
      'number' => $number,
      'hardMode' => $hardMode
    ]);

    return $this;
  }
}
