<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Support\Str;

abstract class Resource {

  static public string $icon = '';
  static public int $priority = 99;

  /**
   * Get the slug of the resource
   *
   * @return string
   */
  static public function slug(): string {
    return Str::kebab(class_basename(get_called_class()));
  }

  /**
   * Get the label of the resource
   *
   * @return string
   */
  static public function label(): string {
    return class_basename(get_called_class());
  }

  /**
   * Get the singular label of the resource
   *
   * @return string
   */
  static public function singular(): string {
    return Str::singular(static::label());
  }
}
