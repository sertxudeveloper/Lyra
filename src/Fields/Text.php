<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class Text extends Field {

  use Placeholder, Sortable;

  public string $component = 'field-text';
  public bool $asHtml = false;

  /**
   * Add field-specific data to the response
   *
   * @param Model $model
   * @return array
   */
  public function additional(Model $model): array {
    return [
      'asHtml' => $this->asHtml,
    ];
  }

  /**
   * Display the field's data as HTML
   *
   * @return $this
   */
  public function asHtml(): self {
    $this->asHtml = true;

    return $this;
  }
}
