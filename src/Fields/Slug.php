<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Align;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class Slug extends Field
{
    use Placeholder, Sortable, Align;

    public string $component = 'field-slug';

    public string $from = '';

    public string $separator = '-';

    /**
     * Add field-specific data to the response
     *
     * @return array
     */
    public function additional(): array {
        return [
            'from' => $this->from,
            'separator' => $this->separator,
        ];
    }

    /**
     * Set the parent field for the slug
     *
     * @param  string  $from
     * @return $this
     */
    public function from(string $from): self {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the separator for the slug
     *
     * @param  string  $separator
     * @return $this
     */
    public function separator(string $separator): self {
        $this->separator = $separator;

        return $this;
    }
}
