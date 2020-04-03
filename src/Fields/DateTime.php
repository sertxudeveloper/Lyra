<?php

namespace SertxuDeveloper\Lyra\Fields;

class DateTime extends Field {

  protected $component = "datetime-field";

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    try {
      $model[$this->data->get('column')] = new \DateTime($field['value']);
    } catch (\Exception $e) {
      abort(500, $e->getMessage());
    }
  }

  /**
   * Get the original value of the Field
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    return (isset($model[$this->data->get('column')])) ? $model[$this->data->get('column')]->format('Y-m-d\TH:i:s') : null;
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
    return parent::getTranslatedValue($model, $type)->format('Y-m-d\TH:i:s');
  }

}
