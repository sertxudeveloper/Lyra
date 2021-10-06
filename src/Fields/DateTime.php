<?php

namespace SertxuDeveloper\Lyra\Fields;

class DateTime extends Field {

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
  public function forceLocale(string $locale): self {
    $this->forceLocale = $locale;
    return $this;
  }
}
