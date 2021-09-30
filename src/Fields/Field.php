<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Str;

abstract class Field {

  public string $component = '';

  public string $name = '';
  public string $column = '';
  public bool $sortable = false;
  public array $creationRules = [];
  public array $updatingRules = [];

  public bool $showOnIndex = true;
  public bool $showOnShow = true;
  public bool $showOnCreate = true;
  public bool $showOnUpdate = true;

  /**
   * Create a new instance of the field
   *
   * @param string $name
   * @param null $column
   * @return $this
   */
  static public function make(string $name, $column = null): self {
    $field = new static();
    $field->name = $name;
    $field->column = $column ?? Str::snake(Str::lower($name));

    return $field;
  }

  /**
   * Set the field as sortable
   *
   * @return $this
   */
  public function sortable(): self {
    $this->sortable = true;
    return $this;
  }

  /**
   * Set the rules for creation and update
   *
   * @param array $rules
   * @return $this
   */
  public function rules(array $rules): self {
    $this->creationRules = $rules;
    $this->updatingRules = $rules;
    return $this;
  }

  /**
   * Set the rules for the creation
   *
   * @param array $rules
   * @return $this
   */
  public function creationRules(array $rules): self {
    $this->creationRules = $rules;
    $this->updatingRules = $rules;
    return $this;
  }

  /**
   * Hide the field on form views
   *
   * @return $this
   */
  public function hideOnForms(): self {
    $this->showOnCreate = false;
    $this->showOnUpdate = false;
    return $this;
  }

}
