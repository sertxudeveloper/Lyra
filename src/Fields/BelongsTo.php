<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsTo extends Relation {

  protected $component = "belongs-to-field";

  public function nullable() {
    $this->data->put('nullable', true);
    return $this;
  }

  protected function getValueIndex($model, $resource) {
    $query = $model->{$this->data->get('column')}();
    if (!$this->data->get('display_column')) $this->data->put('display_column', $query->getOwnerKeyName());
    $item = $model->{$this->data->get('column')};
    return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$this->data->get('display_column')]];
  }

  protected function getValueShow($model, $resource) {
    return $this->getValueIndex($model, $resource);
  }

  protected function getValueEdit($model, $resource) {
    $query = $model->{$this->data->get('column')}();
    $field = $this;
    if (!$field->data->get('display_column')) $field->data->put('display_column', $query->getOwnerKeyName());
    $options = $this->data->get('resource')::$model::all()->map(function ($item) use ($field, $query) {
      return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$field->data->get('display_column')]];
    });
    $this->data->put('options', $options);
    $item = $model->{$this->data->get('column')};
    return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$this->data->get('display_column')]];
  }

  protected function getValueCreate($model, $resource) {
    $query = $model->{$this->data->get('column')}();
    $field = $this;

    if (!$field->data->get('display_column')) $field->data->put('display_column', $query->getOwnerKeyName());
    $options = $this->data->get('resource')::$model::all()->map(function ($item) use ($field, $query) {
      return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$field->data->get('display_column')]];
    });
    $this->data->put('options', $options);

    if (request()->get($query->getForeignKeyName())) {
      $this->data->put('disabled', true);
      $element = $this->data->get('resource')::$model::find(request()->get($query->getForeignKeyName()));
      return $element[$query->getOwnerKeyName()];
    }

    return null;
  }

  public function saveValue($field, $model) {
    $query = $model->{$this->data->get('column')}();
    $model[$query->getForeignKeyName()] = $field['value']['key'] ?: null;
  }

}
