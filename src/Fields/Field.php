<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Str;

class Field {

  protected $component;

  protected $callback = null;

  protected $hideOnIndex = false;
  protected $hideOnShow = false;
  protected $hideOnCreate = false;
  protected $hideOnEdit = false;
  protected $value = null;

  protected $data = null;

  public static function make($name, $column = null) {
    $class = new static();
    $class->data = collect([]);

    if (!$column) {
      $column = Str::snake($name);
    } else {
      if (is_callable($column)) {
        $class->callback = $column;
        $column = strtolower($name);
        $class->hideOnCreate = true;
        $class->hideOnEdit = true;
      }
    }

    $class->data->put('component', $class->component);
    $class->data->put('name', $name);
    $class->data->put('column', $column);
    return $class;
  }

  public function description($text = null) {
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

  public function hideOnIndex($hide = true) {
    $this->hideOnIndex = $hide;
    return $this;
  }

  public function hideOnShow($hide = true) {
    $this->hideOnShow = $hide;
    return $this;
  }

  public function hideOnCreate($hide = true) {
    $this->hideOnCreate = $hide;
    return $this;
  }

  public function hideOnEdit($hide = true) {
    $this->hideOnEdit = $hide;
    return $this;
  }

  public function getPermissions() {
    return [
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }

  public function getValue($model, $type, $resource) {
    if (is_callable($this->callback)) {
      $this->callback = $this->callback->bindTo($model);
      $this->data->put('value', call_user_func($this->callback));
    } else {
      switch ($type) {
        case 'index':
          $value = $this->getValueIndex($model, $resource);
          $this->data->put('value', $value);
          break;

        case 'show':
          $value = $this->getValueShow($model, $resource);
          $this->data->put('value', $value);
          break;

        case 'edit':
          $value = $this->getValueEdit($model, $resource);
          $this->data->put('value', $value);
          break;

        case 'create':
          $value = $this->getValueCreate($model, $resource);
          $this->data->put('value', $value);
          break;

        default:
          $this->data->put('value', null);
          break;
      }
    }

    return $this->get();
  }

  protected function getValueIndex($model, $resource) {
    $this->setPrimary($resource, $model);
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function getValueShow($model, $resource) {
    $this->setPrimary($resource, $model);
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function getValueEdit($model, $resource) {
    $this->setPrimary($resource, $model);
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function getValueCreate($model, $resource) {
    $this->setPrimary($resource, $model);
    if (config('lyra.translator.enabled')) return $this->getTranslatedValue($model);
    return $this->retrieveValue($model);
  }

  protected function retrieveValue($model) {
    return isset($model[$this->data->get('column')]) ? $model[$this->data->get('column')] : null;
  }

  protected function getTranslatedValue($model) {
    if (request()->get('lang') && request()->get('lang') !== config('lyra.translator.default_locale')) {
      return $model->getTranslated($this->data->get('column'), request()->get('lang'));
    }
    return $this->retrieveValue($model);
  }

  protected function setPrimary($resource, $model) {
    if (method_exists($model, 'trashed')) $this->data->put('soft_deleted', $model->trashed());
    $this->data->put('primary', $this->data->get('column') === $resource::getPrimary());
  }

  public function isPrimary($resource) {
    return $this->data->get('column') === $resource::getPrimary();
  }

  public function isTranslatable() {
    return $this->data->get('translatable');
  }

  public function get() {
    return $this->data->toArray();
  }

  public function saveValue($field, $model) {
    $model[$this->data->get('column')] = $field['value'];
  }

}
