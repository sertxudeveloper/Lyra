<?php

namespace SertxuDeveloper\Lyra\Fields;

use SertxuDeveloper\Lyra\Fields\Concerns\Placeholder;

class Markdown extends Field
{
    use Placeholder;

    public string $component = 'field-markdown';

    public bool $showOnIndex = false;
}
