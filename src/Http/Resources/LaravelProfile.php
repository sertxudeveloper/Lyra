<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use App\User;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;

class LaravelProfile extends Resource {

  public static $model = User::class;
  public static $search = ["id", "name", "email"];

  public $labels = ["singular" => "User", "plural" => "Users"];

  public function fields() {

    return [
      Id::make('Id')->description('Campo autoincrementable'),
      Text::make('Name')->size(50)->sortable(),
      Text::make('Email')->description('Also used to log in')->sortable(),
      Password::make('Password'),
    ];
  }

}
