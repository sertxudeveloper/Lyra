<?php

namespace SertxuDeveloper\Lyra\Fields\Concerns;

trait Sortable
{
    public bool $sortable = false;

    /**
     * Set the field as sortable.
     *
     * @return $this
     */
    public function sortable(): static {
        $this->sortable = true;

        return $this;
    }
}
