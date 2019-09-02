<?php

namespace SertxuDeveloper\Lyra\Fields;

class Password extends Field {

  protected $component = "password-field";

  protected $hideOnIndex = true;
  protected $hideOnShow = true;
  const PLACEHOLDER_PASSWORD = "**********";

  protected function retrieveValue($model) {
    $this->data->put('placeholder', self::PLACEHOLDER_PASSWORD);
    return null;
  }

  public function saveValue($field, $model) {
    if ($field['value']) $model[$this->data->get('column')] = $field['value'];
  }
}
