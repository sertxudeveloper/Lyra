<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;

class Markdown extends Field
{
    use Placeholder;

    public string $component = 'field-markdown';

    public bool $showOnIndex = false;
}
