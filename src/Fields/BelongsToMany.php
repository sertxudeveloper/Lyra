<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsToMany extends Relation {

  protected $component = "belongs-to-many-field";

  protected function getValueIndex($model) {
    $field = $this;
    return $model->{$this->column}->map(function ($element) use ($field) {
      return ['key' => $element[$field->foreign_column], 'value' => $element[$field->display_column]];
    });
  }

  protected function getValueShow($model) {
    return $this->getValueIndex($model);
  }

  protected function getValueEdit($model) {
    $options = $this->class::all();
    $field = $this;
    $value = collect([]);
    $this->options = $options->map(function ($element) use ($field, $value, $model) {
      $count = $model[$this->column]->where($this->foreign_column, $element[$this->foreign_column])->count();
      if ($count) $value->push(['key' => $element[$field->foreign_column], 'value' => $element[$field->display_column]]);
      return ['key' => $element[$field->foreign_column], 'value' => $element[$field->display_column]];
    });

    return $value;
  }

  protected function getValueCreate($model) {
    $value = $this->class::all();
    $field = $this;
    $this->options = $value->map(function ($element) use ($field) {
      return ['key' => $element[$field->foreign_column], 'value' => $element[$field->display_column]];
    });
    return [];
  }

  public function saveValue($field, $resource) {
    $keys = collect($field['value'])->pluck('key');
    $resource->{$this->column}()->sync($keys);
  }
}
