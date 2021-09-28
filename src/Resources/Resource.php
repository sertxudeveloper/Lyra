<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Support\Str;

abstract class Resource {

  protected string $icon = '';
  protected ?string $name = null;
  protected ?string $slug = null;

  public function getName(): string {
    return $this->name ?? Str::singular((new \ReflectionClass($this))->getShortName());
  }

  public function getSlug(): string {
    return $this->slug ?? strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', self::getName())));
  }

  public function getIcon(): string {
    return $this->icon;
  }
}
