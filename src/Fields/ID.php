<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Concerns\Align;
use SertxuDeveloper\Lyra\Fields\Concerns\Sortable;

class ID extends Field
{
    use Sortable, Align;

    public string $component = 'field-id';

    public bool $showOnCreate = false;
}
