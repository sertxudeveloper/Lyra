<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class Text extends Field {

  use Placeholder, Sortable;

  public string $component = 'field-text';
  public bool $asHtml = false;
  public string $textAlign = 'left';

  /**
   * Add field-specific data to the response
   */
  public function additional(): array {
    return [
      'asHtml' => $this->asHtml,
      'textAlign' => $this->textAlign,
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

  /**
   * Set the text align to left
   *
   * @return $this
   */
  public function textLeft(): self {
    $this->textAlign = 'left';
    return $this;
  }

  /**
   * Set the text align to right
   *
   * @return $this
   */
  public function textRight(): self {
    $this->textAlign = 'right';
    return $this;
  }

  /**
   * Set the text align to center
   *
   * @return $this
   */
  public function textCenter(): self {
    $this->textAlign = 'center';
    return $this;
  }

  /**
   * Set the text align to justify
   *
   * @return $this
   */
  public function textJustify(): self {
    $this->textAlign = 'justify';
    return $this;
  }
}
