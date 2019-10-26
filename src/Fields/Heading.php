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

  public static function make($text) {
    $class = new static();
    $class->data = collect([]);
    $class->data->put('component', $class->component);
    $class->data->put('value', $text);
    return $class;
  }

  public function hideOnIndex($hide = true) {
    $this->hideOnIndex = $hide;
    return $this;
  }

  public function hideOnShow($hide = true) {
    $this->hideOnShow = $hide;
    return $this;
  }

  public function hideOnCreate($hide = true) {
    $this->hideOnCreate = $hide;
    return $this;
  }

  public function hideOnEdit($hide = true) {
    $this->hideOnEdit = $hide;
    return $this;
  }

  public function isTranslatable() {
    return false;
  }

  public function saveValue(){
    return null;
  }

  public function getPermissions() {
    return [
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }

  public function getValue($model, $type) {
    return $this->data->toArray();
  }
}
