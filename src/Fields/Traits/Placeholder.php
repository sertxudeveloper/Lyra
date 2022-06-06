<?php

namespace SertxuDeveloper\Lyra\Fields\Traits;

trait Placeholder
{
    public string $placeholder;

    /**
     * Set the field placeholder.
     *
     * @param  string  $placeholder
     * @return $this
     */
    public function placeholder(string $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
