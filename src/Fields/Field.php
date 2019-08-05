<?php

namespace SertxuDeveloper\Lyra\Fields;

class Field {

  protected $component;

  protected $description;

  protected $name = null;
  protected $column = null;
  protected $callback = null;

  protected $sortable = false;
  protected $primary = false;
  protected $size = false;

  protected $hideOnIndex = false;
  protected $hideOnShow = false;
  protected $hideOnCreate = false;
  protected $hideOnEdit = false;
  protected $value = null;

  public static function make($name, $column = null) {
    $class = new static();
    if (!$column) {
      $column = strtolower($name);
    } else {
      if (is_callable($column)) {
        $class->callback = $column;
        $column = strtolower($name);
        $class->hideOnCreate = true;
        $class->hideOnEdit = true;
      }
    }
    $class->name = $name;
    $class->column = $column;
    return $class;
  }

  public function description($text = null) {
    $this->description = $text;
    return $this;
  }

  public function sortable() {
    $this->sortable = true;
    return $this;
  }

  public function primary() {
    $this->primary = true;
    return $this;
  }

  public function size($number = null) {
    $this->size = $number;
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

  public function getValue($model, $type) {
    if (is_callable($this->callback)) {
      $this->value = call_user_func($this->callback, $model);
    } else {
      switch ($type) {
        case 'index':
          $this->value = $this->getValueIndex($model);
          break;

        case 'show':
          $this->value = $this->getValueShow($model);
          break;

        case 'edit':
          $this->value = $this->getValueEdit($model);
          break;

        case 'create':
          $this->value = $this->getValueCreate($model);
          break;

        default:
          $this->value = null;
          break;
      }
    }
    return $this->get();
  }

  protected function getValueIndex($model) {
    return $this->retrieveValue($model);
  }

  protected function getValueShow($model) {
    return $this->retrieveValue($model);
  }

  protected function getValueEdit($model) {
    return $this->retrieveValue($model);
  }

  protected function getValueCreate($model) {
    return $this->retrieveValue($model);
  }

  protected function retrieveValue($model) {
    return isset($model[$this->column]) ? $model[$this->column] : null;
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => $this->name,
      "column" => $this->column,
      "description" => $this->description,
      "sortable" => $this->sortable,
      "primary" => $this->primary,
      "size" => $this->size,
      "value" => $this->value,
    ];
  }

  public function updateValue($value) {
    return $value;
  }

}
