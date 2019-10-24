<?php

namespace SertxuDeveloper\Lyra\Fields;

class Header {

  protected $component = 'header-field';

  protected $callback = null;

  protected $hideOnIndex = true;
  protected $hideOnShow = false;
  protected $hideOnCreate = true;
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

  public function getPermissions() {
    return [
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }

  public function get() {
    return $this->data->toArray();
  }


  public function getValue($model, $type, $resource) {
    return $this->get();
  }
}
