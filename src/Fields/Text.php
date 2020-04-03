<?php

namespace SertxuDeveloper\Lyra\Fields;

class Text extends Field {

  protected $component = "text-field";

  /**
   * Enable the character count limiter
   *
   * @param int $number
   * @param bool $hardMode
   * @return $this
   */
  public function size(int $number, bool $hardMode = false) {
    $this->data->put('size', ['number' => $number, 'hardMode' => $hardMode]);
    return $this;
  }
}
