<?php

namespace SertxuDeveloper\Lyra\Fields;

class Date extends Field {

  protected $component = "date-field";

  protected function retrieveValue($model) {
    return isset($model[$this->data->get('column')]) ? $model[$this->data->get('column')]->format('Y-m-d') : null;
  }
}
