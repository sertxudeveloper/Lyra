<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Align;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

class ID extends Field
{
    use Sortable, Align;

    public string $component = 'field-id';

    public bool $showOnCreate = false;
}
