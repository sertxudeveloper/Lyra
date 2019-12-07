<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Str;

abstract class Field {

  protected $component;

  protected $callback = null;

  protected $hideOnIndex = false;
  protected $hideOnShow = false;
  protected $hideOnCreate = false;
  protected $hideOnEdit = false;

  protected $data = null;

  public static function make(string $name, $column = null) {
    $class = new static();
    $class->data = collect([]);

    if (!$column) {
      $column = Str::snake($name);
    } else {
      if (is_callable($column) && gettype($column) === 'object') {
        $class->callback = $column;
        $column = Str::snake($name);
        $class->hideOnCreate = true;
        $class->hideOnEdit = true;
      }
    }

    $class->data->put('component', $class->component);
    $class->data->put('name', $name);
    $class->data->put('column', $column);
    return $class;
  }

  public function description(string $text) {
    $this->data->put('description', $text);
    return $this;
  }

  public function sortable() {
    $this->data->put('sortable', true);
    return $this;
  }

  public function translatable() {
    $this->data->put('translatable', true);
    return $this;
  }

  public function isTranslatable() {
    return (bool) $this->data->get('translatable');
  }

  public function hideOnIndex(bool $hide = true) {
    $this->hideOnIndex = $hide;
    return $this;
  }

  public function hideOnShow(bool $hide = true) {
    $this->hideOnShow = $hide;
    return $this;
  }

  public function hideOnCreate(bool $hide = true) {
    $this->hideOnCreate = $hide;
    return $this;
  }

  public function hideOnEdit(bool $hide = true) {
    $this->hideOnEdit = $hide;
    return $this;
  }

  public function saveValue(array $field, $model) {
    $model[$this->data->get('column')] = $field['value'];
  }

  public function getPermissions() {
    return [
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
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
          $value = $this->getValueEdit($model);
          break;
      }
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
  }

  protected function getValueShow($model) {
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function getValueEdit($model) {
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function getTranslatedValue($model) {
    if (request()->get('lang') && request()->get('lang') !== config('lyra.translator.default_locale')) {
      return $model->getTranslated($this->data->get('column'), request()->get('lang'));
    }
    return $this->retrieveValue($model);
  }

  protected function retrieveValue($model) {
    return isset($model[$this->data->get('column')]) ? $model[$this->data->get('column')] : null;
  }

}
