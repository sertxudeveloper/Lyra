<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class Slug extends Field
{
    use Placeholder, Sortable;

    public string $component = 'field-slug';

    public string $from = '';

    public string $separator = '-';

    /**
     * Add field-specific data to the response
     */
    public function additional(Model $model): array {
        return [
            'from' => $this->from,
            'separator' => $this->separator,
        ];
    }

    /**
     * Set the parent field for the slug
     *
     * @return $this
     */
    public function from(string $from): self {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the separator for the slug
     *
     * @return $this
     */
    public function separator(string $separator): self {
        $this->separator = $separator;

        return $this;
    }
}
