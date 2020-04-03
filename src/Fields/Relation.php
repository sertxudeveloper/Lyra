<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Lyra;

abstract class Relation extends Field {

  /**
   * Set the resource of the related element
   *
   * @param $resource
   * @return $this
   */
  public function setResource($resource) {
    $this->data->put('resource', $resource);
    $resources = Lyra::getResources();
    $this->data->put('path', array_search($resource, $resources));
    $this->data->put('display_column', $resource::$title ?? 'id');
    return $this;
  }

  /**
   * Set the column to display
   *
   * @param string $column
   * @return $this
   */
  public function setDisplay(string $column) {
    $this->data->put('display_column', $column);
    return $this;
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    // Method disabled
  }
}
