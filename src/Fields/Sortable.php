<?php

namespace SertxuDeveloper\Lyra\Fields;

trait Sortable {

  public bool $sortable = false;

  /**
   * Set the field as sortable
   *
   * @return $this
   */
  public function sortable() {
    $this->sortable = true;

    return $this;
  }
}
