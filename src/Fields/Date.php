<?php

namespace SertxuDeveloper\Lyra\Fields;

class Date extends Field {

  protected $component = "date-field";

  /**
   * Get the original value of the Field
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    return (isset($model[$this->data->get('column')])) ? $model[$this->data->get('column')]->format('Y-m-d') : null;
  }

  /**
   * Get the translated value of the Field
   * The language is specified as a request GET input
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getTranslatedValue($model, string $type) {
    return parent::getTranslatedValue($model, $type)->format('Y-m-d');
  }
}
