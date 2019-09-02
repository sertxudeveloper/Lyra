<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Lyra;

class Relation extends Field {

  public function setResource($resource) {
    $this->data->put('resource', $resource);
    $resources = Lyra::getResources();
    $this->data->put('path', array_search($resource, $resources));
    return $this;
  }

  public function setDisplay($column) {
    $this->data->put('display_column', $column);
    return $this;
  }

  public function saveValue($field, $model) {
    return false;
  }
}
