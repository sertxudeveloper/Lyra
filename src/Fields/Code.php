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

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    $model[$this->data->get('column')] = json_decode($field['value']);
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
      if (!$value) return null;
      if ($this->data->has('json')) {
        return json_encode($value,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      } else {
        return $value;
      }
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
    if (!isset($model[$this->data->get('column')])) return null;
    if ($this->data->has('json')) {
      return json_encode($model[$this->data->get('column')], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } else {
      return $model[$this->data->get('column')];
    }
  }
}
