<?php

namespace SertxuDeveloper\Lyra\Fields;

class Boolean extends Field {

  protected $component = "boolean-field";

  protected $trueValue;
  protected $falseValue;
  protected $checked = false;

  /**
   * Set the true and false values
   *
   * @param $true
   * @param $false
   * @return $this
   */
  public function values($true, $false) {
    $this->trueValue = $true;
    $this->falseValue = $false;
    return $this;
  }

  /**
   * Check the boolean by default
   *
   * @return $this
   */
  public function checked() {
    $this->checked = true;
    return $this;
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    $model[$this->data->get('column')] = $field['value'] ? $this->trueValue : $this->falseValue;
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
    if (request()->input('lang') && request()->input('lang') !== config('lyra.translator.default_locale')) {
      $value = $model->getTranslated($this->data->get('column'), request()->input('lang'));
      return ($value == $this->trueValue) ? true : false;
    }
    return $this->getOriginalValue($model, $type);
  }

  /**
   * Get the original value of the Field
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    if (isset($model[$this->data->get('column')])) {
      return ($model[$this->data->get('column')] == $this->trueValue) ? true : false;
    }
    return $this->checked;
  }

}
