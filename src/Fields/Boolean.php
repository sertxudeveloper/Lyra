<?php

namespace SertxuDeveloper\Lyra\Fields;

class Boolean extends Field {

  protected $component = "boolean-field";

  protected function retrieveValue($model) {
    if(isset($model[$this->data->get('column')])) {
      return ($model[$this->data->get('column')] == 'ACTIVE') ? true : false;
    } else {
      return false;
    }
  }

  public function saveValue($field, $resource) {
    $resource[$this->data->get('column')] = $field['value'] ? 'ACTIVE' : 'BLOCKED';
  }

}
