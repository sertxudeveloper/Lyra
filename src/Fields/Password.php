<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Password extends Field {

  public string $component = 'field-password';

  public bool $showOnIndex = false;

  /**
   * Transform the field into an array.
   *
   * @param Model $model
   * @return array
   */
  public function toArray(Model $model): array {
    $field = [
      'key' => $this->getKey(),
      'component' => $this->component,
      'name' => $this->name,
      'value' => Str::repeat('*', 12),
    ];

    return array_merge($field, $this->additional());
  }

  /**
   * Update the field value using the given data
   *
   * @param Model $model The model to be updated
   * @param array $data The new validated data
   * @return void
   */
  public function save(Model $model, array $data): void {
    if ($data[$this->getKey()]) {
      $model->{$this->getKey()} = $data[$this->getKey()];
    }
  }
}
