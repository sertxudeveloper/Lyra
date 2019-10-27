<?php

namespace SertxuDeveloper\Lyra\Fields;

class Slug extends Text {

  protected $component = "slug-field";

  public function slugify(string $id) {
    $this->data->put('target', $id);
    return $this;
  }
}
