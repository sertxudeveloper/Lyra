<?php

namespace SertxuDeveloper\Lyra;

class Sidebar {

  /**
   * Get sidebar resource elements
   *
   * @return array
   */
  static public function items(): array {
    $items = collect();

    foreach (Lyra::$resources as $class) {
      $items->push([
        'name' => $class::label(),
        'slug' => $class::slug(),
        'icon' => $class::$icon,
        'priority' => $class::$priority,
      ]);
    }

    return $items->sortBy('priority')->all();
  }
}
