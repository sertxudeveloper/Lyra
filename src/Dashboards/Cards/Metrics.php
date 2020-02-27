<?php

namespace SertxuDeveloper\Lyra\Dashboards\Cards;

abstract class Metrics extends Card {

  protected $component = "card-metrics";
  protected $subtitle = "";

  protected function difference() {

  }

  public function get() {
    return [
      "title" => $this->title,
      "subtitle" => $this->subtitle,
      "value" => $this->value(),
      "difference" => $this->difference(),
      "icon" => $this->icon,
      "class" => $this->class,
      "component" => $this->component,
    ];
  }
}
