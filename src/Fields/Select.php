<?php

namespace SertxuDeveloper\Lyra\Fields;

class Select extends Field {

  protected $component = "select-field";

  public function options(array $options) {
    if (!$this->isSequential($options)) {
      $options = $this->optionsAssoc($options);
    } else {
      $options = $this->optionsSeque($options);
    }

    $this->data->put("options", $options);
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

  private function isSequential(array $array) {
    return array_keys($array) === range(0, count($array) - 1);
  }

  protected function retrieveValue($model) {
    if (isset($model[$this->data->get('column')])) {
      $index = array_search($model[$this->data->get('column')], array_column($this->data->get('options'), 'key'));
      if ($index === false) return abort(500, "Value {$model[$this->data->get('column')]} not found in the field options");
      return $this->data->get('options')[$index]['value'];
    }

    return null;
  }
}
