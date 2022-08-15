<?php

namespace SertxuDeveloper\Lyra\Fields\Concerns;

trait Align
{
    public string $align = 'left';

    /**
     * Set the text align to center.
     *
     * @return $this
     */
    public function textCenter(): static {
        $this->align = 'center';

        return $this;
    }

    /**
     * Set the text align to justify.
     *
     * @return $this
     */
    public function textJustify(): static {
        $this->align = 'justify';

        return $this;
    }

    /**
     * Set the text align to left.
     *
     * @return $this
     */
    public function textLeft(): static {
        $this->align = 'left';

        return $this;
    }

    /**
     * Set the text align to right.
     *
     * @return $this
     */
    public function textRight(): static {
        $this->align = 'right';

        return $this;
    }
}
