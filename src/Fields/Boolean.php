<?php

namespace SertxuDeveloper\Lyra\Fields;

class Boolean extends Field {

  protected $component = "boolean-field";

  protected $trueValue;
  protected $falseValue;
  protected $checked = false;

  public function setValues($true, $false) {
    $this->trueValue = $true;
    $this->falseValue = $false;
    return $this;
  }

  public function checked() {
    $this->checked = true;
    return $this;
  }

  protected function retrieveValue($model) {
    if (isset($model[$this->data->get('column')])) {
      return ($model[$this->data->get('column')] == $this->trueValue) ? true : false;
    }
    return $this->checked;
  }

  public function saveValue($field, $resource) {
    $resource[$this->data->get('column')] = $field['value'] ? $this->trueValue : $this->falseValue;
  }

}
