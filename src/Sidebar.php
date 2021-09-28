<?php

namespace SertxuDeveloper\Lyra;

class Sidebar {

  static public function items(): array {
    $items = [];

    foreach (Lyra::resources() as $class) {
      $class = new $class;

      $item = [
        'name' => $class->getName(),
        'slug' => $class->getSlug(),
        'icon' => $class->getIcon(),
      ];

      array_push($items, $item);
    }

    return $items;
  }
}
