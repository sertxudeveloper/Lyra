<?php

namespace SertxuDeveloper\Lyra\Dashboards\Cards;

abstract class OrderedList {

  protected $title;
  protected $suffix;
  protected $class = "col-5 col-lg-4 col-xl-4";
  protected $component = "card-ordered-list";

  protected function value() {
    return [];
  }

  public function get() {
    return [
      "title" => $this->title,
      "suffix" => $this->suffix,
      "value" => $this->value(),
      "class" => $this->class,
      "component" => $this->component,
    ];
  }
}
