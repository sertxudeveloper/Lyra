<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use App\User as Model;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;

class LaravelUser extends Resource {

  public static $model = Model::class;
  public static $search = ["id", "name", "email"];
  public static $title = "name";
  public static $subtitle = "email";

  public $singular = "User";
  public $plural = "Users";

  public function fields() {

    return [
      Id::make('Id')->description('Autoincrement'),
      Text::make('Name')->rules('required'),
      Text::make('Email')->rules('required', "unique:users,email,{{id}}"),
      Password::make('Password')->rules('nullable', 'string', 'min:8'),
    ];
  }

}
