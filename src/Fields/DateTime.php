<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class DateTime extends Field {

  use Sortable;

  public string $component = 'field-datetime';
  public string $timezone = '';
  public string $locale = '';

  /**
   * Add field-specific data to the response
   */
  public function additional(): array {
    return [
      'timezone' => $this->timezone ?: false,
      'locale' => $this->locale ?: false,
    ];
  }

  /**
   * Set the locale of the field
   *
   * @param string $locale Example: 'es', 'en'
   * @return $this
   */
  public function locale(string $locale): static {
    $this->locale = $locale;

    return $this;
  }
}
