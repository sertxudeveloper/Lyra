<?php


namespace SertxuDeveloper\Lyra\Fields;


class Relation extends Field {

  protected $foreign_column;
  protected $display_column;
  protected $class;

  protected $options;

  public function setForeign($foreign_column, $display_column, $class) {
    $this->foreign_column = $foreign_column;
    $this->display_column = $display_column;
    $this->class = $class;
    return $this;
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => $this->name,
      "column" => $this->column,
      "description" => $this->description,
      "sortable" => $this->sortable,
      "primary" => $this->primary,
      "options" => $this->options,
      "value" => $this->value,
    ];
  }

}
