<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class DateTime extends Field {

  use Placeholder, Sortable;

  public string $component = 'field-datetime';

  /**
   * Add field-specific data to the response
   *
   * @param Model $model
   * @return array
   */
  public function additional(Model $model): array {
    return [
      'timezone' => config('app.timezone'),
      'locale' => config('app.locale'),
    ];
  }
}
