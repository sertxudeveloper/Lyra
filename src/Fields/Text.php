<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Concerns\Align;
use SertxuDeveloper\Lyra\Fields\Concerns\Placeholder;
use SertxuDeveloper\Lyra\Fields\Concerns\Sortable;

class Text extends Field
{
    use Placeholder, Sortable, Align;

    protected string $component = 'field-text';

    public bool $asHtml = false;

    /**
     * Display the field's data as HTML.
     *
     * @return $this
     */
    public function asHtml(): self {
        $this->asHtml = true;

        return $this;
    }
}
