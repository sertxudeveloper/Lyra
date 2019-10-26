<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsTo extends Relation {

  protected $component = "belongs-to-field";

  public function nullable() {
    $this->data->put('nullable', true);
    return $this;
  }

  public function getValue($model, $type) {
    $value = null;

    if (is_callable($this->callback)) {
      $this->callback = $this->callback->bindTo($model);
      $value = call_user_func($this->callback);
    } else {
      switch ($type) {
        case 'index':
          $value = $this->getValueShow($model);
          break;

        case 'show':
          $value = $this->getValueShow($model);
          break;

        case 'edit':
          $value = $this->getValueEdit($model);
          break;

        case 'create':
          $value = $this->getValueCreate($model);
          break;
      }
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
  }

  protected function getValueShow($model) {
    $query = $model->{$this->data->get('column')}();
    if (!$this->data->get('display_column')) $this->data->put('display_column', $query->getOwnerKeyName());
    $item = $model->{$this->data->get('column')};
    return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$this->data->get('display_column')]];
  }

  protected function getValueCreate($model) {
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
      return ['key' => $element[$query->getOwnerKeyName()], 'value' => $element[$field->data->get('display_column')]];
    }

    return null;
  }

  protected function getValueEdit($model) {
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

  public function saveValue($field, $model) {
    $query = $model->{$this->data->get('column')}();
    $model[$query->getForeignKeyName()] = $field['value']['key'] ?: null;
  }

}
