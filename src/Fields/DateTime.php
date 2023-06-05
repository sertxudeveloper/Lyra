<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Concerns\Align;
use SertxuDeveloper\Lyra\Fields\Concerns\Placeholder;
use SertxuDeveloper\Lyra\Fields\Concerns\Sortable;

class DateTime extends Field
{
    use Placeholder, Sortable, Align;

    public string $component = 'field-datetime';

    /**
     * Add field-specific data to the response
     *
     * @return array
     */
    public function additional(): array {
        return [
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];
    }
}
