<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class DateTime extends Field {

  use Placeholder, Sortable;

  public string $component = 'field-datetime';

  /**
   * Add field-specific data to the response
   *
   * @return array
   */
  public function additional(): array {
    return [
      'timezone' => config('app.timezone'),
      'locale' => config('app.locale'),
    ];
  }
}
