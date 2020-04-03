<?php

namespace SertxuDeveloper\Lyra\Fields;

class Heading {

  protected $component = 'heading-field';

  protected $callback = null;

  protected $hideOnIndex = true;
  protected $hideOnShow = false;
  protected $hideOnCreate = false;
  protected $hideOnEdit = false;

  protected $data = null;

  /**
   * Create a new instance of the Field
   *
   * @param string $text
   * @return self
   */
  public static function make(string $text) {
    $class = new static();
    $class->data = collect([]);
    $class->data->put('component', $class->component);
    $class->data->put('value', $text);
    return $class;
  }

  /**
   * Overwrite hideOnIndex visibility
   *
   * @param bool $hide
   * @return $this
   */
  public function hideOnIndex(bool $hide = true): self {
    $this->hideOnIndex = $hide;
    return $this;
  }

  /**
   * Overwrite hideOnShow visibility
   *
   * @param bool $hide
   * @return $this
   */
  public function hideOnShow(bool $hide = true): self {
    $this->hideOnShow = $hide;
    return $this;
  }

  /**
   * Overwrite hideOnCreate visibility
   *
   * @param bool $hide
   * @return $this
   */
  public function hideOnCreate(bool $hide = true): self {
    $this->hideOnCreate = $hide;
    return $this;
  }

  /**
   * Overwrite hideOnEdit visibility
   *
   * @param bool $hide
   * @return $this
   */
  public function hideOnEdit(bool $hide = true): self {
    $this->hideOnEdit = $hide;
    return $this;
  }

  /**
   * Return true if the Field is primary or false if not
   *
   * @return bool
   */
  public function isPrimary(): bool {
    return false;
  }

  /**
   * Get the visibility of the Field
   *
   * @return array
   */
  public function getVisibility(): array {
    return [
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }

  /**
   * Get the value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return array
   */
  public function getValue($model, $type) {
    return $this->data->toArray();
  }
}
