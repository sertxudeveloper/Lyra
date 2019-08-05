<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsTo extends Relation {

  protected $component = "belongs-to-field";
  protected $disabled;

  protected function getValueIndex($model) {
    return $this->class::find($model[$this->column])[$this->display_column];
  }

  protected function getValueShow($model) {
    return $this->class::find($model[$this->column])[$this->display_column];
  }

  protected function getValueEdit($model) {
    $field = $this;
    $this->options = $this->class::all()->map(function ($item) use ($field) {
      return ['key' => $item[$field->foreign_column], 'value' => $item[$field->display_column]];
    });
    return $model[$this->column];
  }

  protected function getValueCreate($model) {
    $field = $this;
    $this->options = $this->class::all()->map(function ($item) use ($field) {
      return ['key' => $item[$field->foreign_column], 'value' => $item[$field->display_column]];
    });

    $param = request()->get($this->column);
    if ($param) $this->disabled = true;
    return ($param) ? $param : null;
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => $this->name,
      "column" => $this->column,
      "description" => $this->description,
      "sortable" => $this->sortable,
      "disabled" => $this->disabled,
      "primary" => $this->primary,
      "options" => $this->options,
      "value" => $this->value,
    ];
  }
}
