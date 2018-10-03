<?php

namespace SertxuDeveloper\Lyra\Http\Widgets;

class UsersWidget extends Widget {

  public static $model = "App\User";
  public $with = [];
  public $width = 'col-3';

  public function publish() {

  }

  public function component() {
    return '';
  }
}