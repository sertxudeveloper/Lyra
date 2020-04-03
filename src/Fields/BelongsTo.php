<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsTo extends Relation {

  protected $component = "belongs-to-field";

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
      if (config('lyra.translator.enabled') && $this->isTranslatable()) {
        $value = $this->getTranslatedValue($model, $type);
      } else {
        $value = $this->getOriginalValue($model, $type);
      }
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
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
    return abort(500, "This field currently doesn't support translations");
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
   * @param $model
   * @return array|null
   */
  private function getOriginalValueCreate($model) {
    $query = $model->{$this->data->get('column')}();

    $options = $this->getAllOptions($query);
    $this->data->put('options', $options);

    if (request()->get($query->getForeignKeyName())) {
      $element = $this->data->get('resource')::$model::find(request()->get($query->getForeignKeyName()));
      return ['key' => $element[$query->getOwnerKeyName()], 'value' => $element[$this->data->get('display_column')]];
    }

    return null;
  }

  /**
   * @param $model
   * @return array
   */
  private function getOriginalValueEdit($model) {
    $query = $model->{$this->data->get('column')}();

    $options = $this->getAllOptions($query);
    $this->data->put('options', $options);

    $item = $model[$this->data->get('column')];
    return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$this->data->get('display_column')]];
  }

  /**
   * @param $model
   * @return array
   */
  private function getOriginalValueShow($model) {
    $query = $model->{$this->data->get('column')}();
    $item = $model[$this->data->get('column')];
    return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$this->data->get('display_column')]];
  }

  /**
   * @param $query
   * @return mixed
   */
  private function getAllOptions($query) {
    return $this->data->get('resource')::$model::all()->map(function ($item) use ($query) {
      return ['key' => $item[$query->getOwnerKeyName()], 'value' => $item[$this->data->get('display_column')]];
    });
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    $query = $model->{$this->data->get('column')}();
    $model[$query->getForeignKeyName()] = $field['value']['key'] ?: null;
  }
}
