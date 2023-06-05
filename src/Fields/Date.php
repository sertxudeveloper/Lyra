<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Concerns\Align;
use SertxuDeveloper\Lyra\Fields\Concerns\Placeholder;
use SertxuDeveloper\Lyra\Fields\Concerns\Sortable;

class Date extends Field
{
    use Placeholder, Sortable, Align;

    public string $component = 'field-date';
}
