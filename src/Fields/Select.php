<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Arr;

class Select extends Field {

  protected $component = "select-field";
  private $defaultValue;

  /**
   * Set the options of the Select field
   *
   * @param array $options
   * @return $this
   */
  public function options(array $options) {
    if (Arr::isAssoc($options)) {
      $options = $this->optionsAssoc($options);
    } else {
      $options = $this->optionsSeque($options);
    }

    $this->data->put("options", $options);
    return $this;
  }

  /**
   * Set the default option
   *
   * @param $value
   * @return $this
   */
  public function default($value) {
    $this->defaultValue = $value;
    return $this;
  }

  private function optionsSeque(array $options) {
    $options = collect($options)->map(function ($option) {
      return ["key" => $option, "value" => $option];
    })->values()->toArray();

    return $options;
  }

  private function optionsAssoc(array $options) {
    $options = collect($options)->map(function ($option, $key) {
      return ["key" => $key, "value" => $option];
    })->values()->toArray();

    return $options;
  }

  /**
   * Get the original value of the Field
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    $key = ($type == 'edit') ? 'key' : 'value';
    if (!isset($model[$this->data->get('column')])) return $this->defaultValue;
    $index = array_search($model[$this->data->get('column')], array_column($this->data->get('options'), 'key'));
    if ($index === false) return abort(500, "Value {$model[$this->data->get('column')]} not found in the field options");
    return $this->data->get('options')[$index][$key] ?? $this->defaultValue ?: null;
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
    $key = ($type == 'edit') ? 'key' : 'value';
    if (request()->input('lang') && request()->input('lang') !== config('lyra.translator.default_locale')) {
      $value = $model->getTranslated($this->data->get('column'), request()->get('lang'));
      if ($value) {
        $index = array_search($value, array_column($this->data->get('options'), 'key'));
        if ($index === false) return abort(500, "Value {$model[$this->data->get('column')]} not found in the field options");
        return $this->data->get('options')[$index][$key];
      }
    }
    return $this->getOriginalValue($model, $type);
  }
}
