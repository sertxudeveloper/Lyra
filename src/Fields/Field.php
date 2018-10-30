<?php

namespace SertxuDeveloper\Lyra\Fields;

abstract class Field {

  protected $component;

  protected static $name;
  protected static $column;

  protected $description;

  protected $sortable = false;
  protected $primary = false;
  protected $size = false;

  protected $hideOnIndex = false;
  protected $hideOnShow = false;
  protected $hideOnCreate = false;
  protected $hideOnEdit = false;

  public static function make(string $name, $column = false) {
    if (!$column) $column = strtolower($name);
    static::$name = $name;
    static::$column = $column;
    return static::getNewInstance();
  }

  public function description($text = null) {
    $this->description = $text;
    return $this;
  }

  public function sortable($bool = true) {
    $this->sortable = $bool;
    return $this;
  }

  public function primary($bool = true) {
    $this->primary = $bool;
    return $this;
  }

  public function size($number = false) {
    $this->size = $number;
    return $this;
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => static::$name,
      "column" => static::$column,
      "description" => $this->description,
      "sortable" => $this->sortable,
      "primary" => $this->primary,
      "size" => $this->size,
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }

  protected abstract static function getNewInstance();
}
