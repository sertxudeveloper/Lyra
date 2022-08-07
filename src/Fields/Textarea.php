<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;

class Textarea extends Field
{
    use Placeholder;

    public string $component = 'field-textarea';

    public bool $showOnIndex = false;

    public int $rows = 5;

    /**
     * Add field-specific data to the response
     *
     * @return array
     */
    public function additional(): array {
        return [
            'rows' => $this->rows,
        ];
    }

    /**
     * Set the rows of the textarea
     *
     * @param  int  $rows
     * @return $this
     */
    public function rows(int $rows): self {
        $this->rows = $rows;

        return $this;
    }
}
