<?php

namespace SertxuDeveloper\Lyra\Fields;

class Slug extends Text {

  protected $component = "slug-field";

  /**
   * Set the target column name to autogenerate the slug
   *
   * @param string $name
   * @return $this
   */
  public function slugify(string $name) {
    $this->data->put('target', $name);
    return $this;
  }
}
