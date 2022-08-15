<?php

namespace SertxuDeveloper\Lyra\Fields\Concerns;

trait Required
{
    public bool $required = false;

    /**
     * Set the field as required.
     *
     * @return $this
     */
    public function required(): static {
        $this->required = true;

        return $this;
    }
}
