<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsToMany extends Relation {

  protected $component = "belongs-to-many-field";
  protected $hideOnIndex = true;

  protected function getValueIndex($model, $resource) {
    $query = $model->{$this->data->get('column')}();
    $field = $this;
    if (!$this->data->get('display_column')) $this->data->put('display_column', $query->getParentKeyName());

    return $model->{$this->data->get('column')}->map(function ($element) use ($field, $query) {
      return ['key' => $element[$query->getParentKeyName()], 'value' => $element[$this->data->get('display_column')]];
    });
  }

  protected function getValueShow($model, $resource) {
    return $this->getValueIndex($model, $resource);
  }

  protected function getValueEdit($model, $resource) {
    $query = $model->{$this->data->get('column')}();
    $field = $this;
    if (!$this->data->get('display_column')) $this->data->put('display_column', $query->getParentKeyName());

    $options = $this->data->get('resource')::$model::all();
    $options = $options->map(function ($element) use ($field, $query) {
      return ['key' => $element[$query->getParentKeyName()], 'value' => $element[$this->data->get('display_column')]];
    });
    $this->data->put('options', $options);

    $value = $model->{$this->data->get('column')}->map(function ($element) use ($field, $query) {
      return ['key' => $element[$query->getParentKeyName()], 'value' => $element[$this->data->get('display_column')]];
    });

    return $value;
  }

  protected function getValueCreate($model, $resource) {
    $query = $model->{$this->data->get('column')}();
    $field = $this;

    if (!$this->data->get('display_column')) $this->data->put('display_column', $query->getParentKeyName());
    $options = $this->data->get('resource')::$model::all()->map(function ($item) use ($field, $query) {
      return ['key' => $item[$query->getParentKeyName()], 'value' => $item[$this->data->get('display_column')]];
    });
    $this->data->put('options', $options);

    if (request()->get($query->getRelatedPivotKeyName())) {
      $element = $this->data->get('resource')::$model::find(request()->get($query->getRelatedPivotKeyName()));
      return [['key' => $element[$query->getRelatedKeyName()], 'value' => $element[$this->data->get('display_column')]]];
    }

    return null;
  }

  public function syncRelationship($field, $resource) {
    $keys = collect($field['value'])->pluck('key');
    $resource->{$this->data->get('column')}()->sync($keys);
  }
}
