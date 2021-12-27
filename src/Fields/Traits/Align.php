<?php

namespace SertxuDeveloper\Lyra\Fields\Traits;

trait Align {

  public string $align = 'left';

  /**
   * Set the text align to center
   *
   * @return $this
   */
  public function textCenter(): self {
    $this->align = 'center';

    return $this;
  }

  /**
   * Set the text align to justify
   *
   * @return $this
   */
  public function textJustify(): self {
    $this->align = 'justify';

    return $this;
  }

  /**
   * Set the text align to left
   *
   * @return $this
   */
  public function textLeft(): self {
    $this->align = 'left';

    return $this;
  }

  /**
   * Set the text align to right
   *
   * @return $this
   */
  public function textRight(): self {
    $this->align = 'right';

    return $this;
  }
}
