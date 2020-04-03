<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Facades\Hash;

class Password extends Field {

  protected $component = "password-field";

  protected $hideOnIndex = true;
  protected $hideOnShow = true;
  const PLACEHOLDER_PASSWORD = "**********";

  /**
   * Get the value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return array
   */
  public function getValue($model, string $type): array {
    $this->data->put('placeholder', self::PLACEHOLDER_PASSWORD);
    $this->data->put('value', null);

    return $this->data->toArray();
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    if ($field['value']) $model[$this->data->get('column')] = Hash::make($field['value']);
  }
}
