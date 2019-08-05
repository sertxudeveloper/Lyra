<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsToMany extends Relation {

  protected $component = "belongs-to-many-field";

  protected function getValueIndex($model) {
    $value = $this->class::where($this->foreign_column, $model[$this->column])->get();
    $field = $this;
    return $value->map(function ($element) use ($field) {
      return ['key' => $element[$field->column], 'value' => $element[$field->display_column]];
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
      if ($model[$this->column] == $element[$this->foreign_column]) $value->push(['key' => $element[$field->column], 'value' => $element[$field->display_column]]);
      return ['key' => $element[$field->column], 'value' => $element[$field->display_column]];
    });
    return $value;
  }

  protected function getValueCreate($model) {
    $value = $this->class::all();
    $field = $this;
    $this->options = $value->map(function ($element) use ($field) {
      return ['key' => $element[$field->column], 'value' => $element[$field->display_column]];
    });
    return [];
  }

}
