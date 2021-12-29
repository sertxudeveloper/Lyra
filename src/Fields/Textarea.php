<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;

class Textarea extends Field {

  use Placeholder;

  public string $component = 'field-textarea';

  public bool $showOnIndex = false;

  public int $rows = 5;

  /**
   * Add field-specific data to the response
   *
   * @param Model $model
   * @return array
   */
  public function additional(Model $model): array {
    return [
      'rows' => $this->rows
    ];
  }

  public function rows(int $rows): self {
    $this->rows = $rows;

    return $this;
  }
}
