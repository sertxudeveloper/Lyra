<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsToMany extends Relation {

  protected $component = "belongs-to-many-field";
  protected $hideOnIndex = true;

  /**
   * Get the value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return array
   */
  public function getValue($model, string $type): array {
    $value = null;

    if (is_callable($this->callback)) {
      $this->callback = $this->callback->bindTo($model);
      $value = call_user_func($this->callback);
    } else {
      if (method_exists($this->data->get('resource')::$model, 'getTranslated')) {
        $value = $this->getTranslatedValue($model, $type);
      } else {
        $value = $this->getOriginalValue($model, $type);
      }
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
  }

  /**
   * Sync the relationship after updating the model
   *
   * @param $field
   * @param $resource
   */
  public function syncRelationship($field, $resource) {
    $keys = collect($field['value'])->pluck('key');
    $resource->{$this->data->get('column')}()->sync($keys);
  }

  /**
   * Get the original value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    if ($type === 'create') {
      return $this->getOriginalValueCreate($model);
    } else if ($type === 'edit') {
      return $this->getOriginalValueEdit($model);
    } else {
      return $this->getOriginalValueShow($model);
    }
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
    if ($type === 'create') {
      return $this->getTranslatedValueCreate($model);
    } else if ($type === 'edit') {
      return $this->getTranslatedValueEdit($model);
    } else {
      return $this->getTranslatedValueShow($model);
    }
  }

  /**
   * @param $query
   * @return mixed
   */
  private function getAllOptions($query) {
    return $this->data->get('resource')::$model::all()->map(function ($element) use ($query) {
      return [
        'key' => $element[$query->getParentKeyName()],
        'value' => $element[$this->data->get('display_column')]
      ];
    });
  }

  /**
   * @param $model
   * @return array|null
   */
  private function getOriginalValueCreate($model) {
    $query = $model->{$this->data->get('column')}();

    $options = $this->getAllOptions($query);
    $this->data->put('options', $options);

    if (request()->get($query->getRelatedPivotKeyName())) {
      $element = $this->data->get('resource')::$model::find(request()->get($query->getRelatedPivotKeyName()));
      return [
        [
          'key' => $element[$query->getRelatedKeyName()],
          'value' => $element[$this->data->get('display_column')]
        ]
      ];
    }

    return null;
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getOriginalValueEdit($model) {
    $query = $model->{$this->data->get('column')}();

    $options = $this->getAllOptions($query);
    $this->data->put('options', $options);

    return $model->{$this->data->get('column')}->map(function ($element) use ($query) {
      return [
        'key' => $element[$query->getParentKeyName()],
        'value' => $element[$this->data->get('display_column')]
      ];
    });
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getOriginalValueShow($model) {
    $query = $model->{$this->data->get('column')}();

    return $model->{$this->data->get('column')}->map(function ($element) use ($query) {
      return [
        'key' => $element[$query->getParentKeyName()],
        'value' => $element[$this->data->get('display_column')]
      ];
    });
  }

  /**
   * @param $model
   * @return array|null
   */
  private function getTranslatedValueCreate($model) {
    $query = $model->{$this->data->get('column')}();

    $options = $this->getAllOptions($query);
    $this->data->put('options', $options);

    if (request()->get($query->getRelatedPivotKeyName())) {
      $element = $this->data->get('resource')::$model::find(request()->get($query->getRelatedPivotKeyName()));
      return [
        [
          'key' => $element[$query->getRelatedKeyName()],
          'value' => $element->getTranslated($this->data->get('display_column'), request()->input('lang'))
        ]
      ];
    }

    return null;
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getTranslatedValueEdit($model) {
    $query = $model->{$this->data->get('column')}();

    $options = $this->getAllOptions($query);
    $this->data->put('options', $options);

    return $model->{$this->data->get('column')}->map(function ($element) use ($query) {
      return [
        'key' => $element[$query->getParentKeyName()],
        'value' => $element->getTranslated($this->data->get('display_column'), request()->input('lang'))
      ];
    });
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getTranslatedValueShow($model) {
    $query = $model->{$this->data->get('column')}();

    return $model->{$this->data->get('column')}->map(function ($element) use ($query) {
      return [
        'key' => $element[$query->getParentKeyName()],
        'value' => $element->getTranslated($this->data->get('display_column'), request()->input('lang'))
      ];
    });
  }
}
