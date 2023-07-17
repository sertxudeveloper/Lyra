<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Support\Str;

abstract class Card
{
    /**
     * Get the label of the card
     */
    public function label(): string {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * Get the slug of the card
     */
    public function slug(): string {
        return Str::kebab(class_basename(get_called_class()));
    }
}
