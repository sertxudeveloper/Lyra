<?php

namespace SertxuDeveloper\Lyra\Fields;

class Code extends Field {

  protected $component = "code-field";

  protected $hideOnIndex = true;

  /**
   * Set the CodeMirror language mode
   *
   * @param $mode
   * @return $this
   */
  public function mode($mode) {
    $this->data->put('mode', $mode);
    return $this;
  }

  /**
   * Set the language mode as JSON
   *
   * @return $this
   */
  public function json() {
    $this->data->put('mode', 'javascript');
    $this->data->put('json', true);
    return $this;
  }
}
