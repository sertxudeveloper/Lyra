<?php

namespace SertxuDeveloper\Lyra\Fields\Traits;

trait Align {

  public string $textAlign = 'left';

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
}
