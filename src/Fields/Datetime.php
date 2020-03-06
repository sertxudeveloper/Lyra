<?php

namespace SertxuDeveloper\Lyra\Fields;

class Datetime extends Field {

  protected $component = "datetime-field";

  protected function retrieveValue($model) {
    return isset($model[$this->data->get('column')]) ? $model[$this->data->get('column')]->format('Y-m-d\TH:i:s') : null;
  }

}
