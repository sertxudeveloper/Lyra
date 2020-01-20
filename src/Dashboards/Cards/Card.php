<?php

namespace SertxuDeveloper\Lyra\Dashboards\Cards;

abstract class Card {

  protected $title;
  protected $icon;
  protected $class = "col-12 col-lg col-xl";
  protected $component = "card-simple";

  protected function value() {
    return 1;
  }

  public function get() {
    return [
      "title" => $this->title,
      "value" => $this->value(),
      "icon" => $this->icon,
      "class" => $this->class,
      "component" => $this->component,
    ];
  }
}
