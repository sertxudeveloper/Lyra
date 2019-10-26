<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Arr;

class Select extends Field {

  protected $component = "select-field";
  private $defaultValue;

  public function options(array $options) {
    if (Arr::isAssoc($options)) {
      $options = $this->optionsAssoc($options);
    } else {
      $options = $this->optionsSeque($options);
    }

    $this->data->put("options", $options);
    return $this;
  }

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

  protected function getValueShow($model) {
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model, 'value');
    return $this->retrieveValue($model, 'value');
  }

  protected function getValueEdit($model) {
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model, 'key');
    return $this->retrieveValue($model, 'key');
  }

  protected function getTranslatedValue($model, $key = 'value') {
    if (request()->get('lang') && request()->get('lang') !== config('lyra.translator.default_locale')) {
      $value = $model->getTranslated($this->data->get('column'), request()->get('lang'));
      if ($value) {
        $index = array_search($value, array_column($this->data->get('options'), 'key'));
        if ($index === false) return abort(500, "Value {$model[$this->data->get('column')]} not found in the field options");
        return $this->data->get('options')[$index][$key];
      }
    }
    return $this->retrieveValue($model, $key);
  }

  protected function retrieveValue($model, $key = 'value') {
    if (isset($model[$this->data->get('column')])) {
      $index = array_search($model[$this->data->get('column')], array_column($this->data->get('options'), 'key'));
      if ($index === false) return abort(500, "Value {$model[$this->data->get('column')]} not found in the field options");
      return $this->data->get('options')[$index][$key];
    }

    return $this->defaultValue ?: null;
  }
}
