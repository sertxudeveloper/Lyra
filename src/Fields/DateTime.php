<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class DateTime extends Field {

  use Sortable;

  public string $component = 'field-datetime';
  public string $forceLocale = '';

  /**
   * Add field-specific data to the response
   */
  public function additional(): array {
    return [
      'forceLocale' => $this->forceLocale ?: false,
    ];
  }

  /**
   * Force the field to the provided locale
   *
   * @param string $locale Example: 'es-ES', 'en-US'
   * @return $this
   */
  public function forceLocale(string $locale): static {
    $this->forceLocale = $locale;

    return $this;
  }
}
