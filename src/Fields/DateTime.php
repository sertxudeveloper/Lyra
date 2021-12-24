<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class DateTime extends Field {

  use Sortable;

  public string $component = 'field-datetime';
//  public string $timezone = '';
//  public string $locale = '';

  /**
   * Add field-specific data to the response
   */
  public function additional(): array {
    return [
      'timezone' => config('app.timezone'),
      'locale' => config('app.locale'),
    ];
  }

//  /**
//   * Set the locale of the field
//   *
//   * @param string $locale Example: 'es', 'en'
//   * @return $this
//   */
//  public function locale(string $locale): static {
//    $this->locale = $locale;
//
//    return $this;
//  }

//  /**
//   * Set the timezone of the field
//   *
//   * @param string $timezone Example: 'Europe/Madrid', 'America/New_York'
//   * @return $this
//   */
//  public function timezone(string $timezone): static {
//    $this->timezone = $timezone;
//
//    return $this;
//  }

  /**
   * Update the field value using the given data
   *
   * @param Model $model The model to be updated
   * @param array $data The new validated data
   * @return void
   */
  public function save(Model $model, array $data): void {
    dd($data[$this->getKey()]);
    $model->{$this->getKey()} = $data[$this->getKey()];
  }
}
