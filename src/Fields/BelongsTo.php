<?php

namespace SertxuDeveloper\Lyra\Fields;

class BelongsTo extends Field {

  protected $component = "belongs-to-field";
  private static $read;
  private static $write;
  private static $class;
  private static $where;

  public static function make(string $name, $column = false, $read = 'name', $write = 'id', $class = false, $where = false) {
    if (!$column) $column = strtolower($name);
    static::$name = $name;
    static::$column = $column;
    static::$read = $read;
    static::$write = $write;
    static::$class = $class;
    static::$where = $where;
    return static::getNewInstance();
  }

  protected static function getNewInstance() {
    return new self();
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => static::$name,
      "column" => static::$column,
      "read" => ["key" => static::$read],
      "write" => ["key" => static::$write],
      "class" => ["key" => static::$class, "where" => static::$where],
      "sortable" => $this->sortable,
      "primary" => $this->primary,
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }
}