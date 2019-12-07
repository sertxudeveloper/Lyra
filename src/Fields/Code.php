<?php

namespace SertxuDeveloper\Lyra\Fields;

class Code extends Field {

  protected $component = "code-field";

  protected $hideOnIndex = true;

  public function mode($mode) {
    $this->data->put('mode', $mode);
    return $this;
  }

  public function json() {
    $this->data->put('mode', 'javascript');
    $this->data->put('json', true);
    return $this;
  }

  protected function getValueShow($model) {
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function getValueEdit($model) {
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }
}
