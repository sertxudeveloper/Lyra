<?php

namespace SertxuDeveloper\Lyra\Fields\Concerns;

trait Placeholder
{
    public string $placeholder;

    /**
     * Set the field's placeholder.
     *
     * @param  string  $placeholder
     * @return $this
     */
    public function placeholder(string $placeholder): static {
        $this->placeholder = $placeholder;

        return $this;
    }
}
