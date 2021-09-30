<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Support\Str;

abstract class Card {

  public string $name = '';

  /**
   * Get the label of the card
   *
   * @return string
   */
  static public function label(): string {
    return Str::title(Str::snake(class_basename(get_called_class()), ' '));
  }

  /**
   * Get the slug of the card
   *
   * @return string
   */
  static public function slug(): string {
    return Str::kebab(static::label());
  }
}
