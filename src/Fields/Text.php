<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Align;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class Text extends Field
{
    use Placeholder, Sortable, Align;

    public string $component = 'field-text';

    public bool $asHtml = false;

    /**
     * Add field-specific data to the response
     *
     * @return array
     */
    public function additional(): array {
        return [
            'asHtml' => $this->asHtml,
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
}
