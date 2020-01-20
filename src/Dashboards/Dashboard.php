<?php

namespace SertxuDeveloper\Lyra\Dashboards;

class Dashboard {

  protected function dashboard() {
    return [
      [
        
      ],
      [
        
      ],
    ];
  }


  public function toArray() {
    $dashboard = [];

    foreach ($this->dashboard() as $rawRow) {
      $cards = [];

      foreach ($rawRow as $column) {
        $cards[] = $column->get();
      }

      $dashboard[] = $cards;
    }

    return $dashboard;
  }
}
