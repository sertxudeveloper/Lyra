<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\BelongsTo;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Models\Role;

class Users extends Resource {

  public static $model = "App\User";
  public static $primary = "id";
  public static $search = ["id", "name", "email"];

  public $labels = [
    "singular" => "User",
    "plural" => "Users"
  ];

  public function fields() {

    return [
      Id::make('Id', 'id')->description('Campo autoincrementable')->get(),
      Text::make('Username', 'name')->size(50)->sortable()->get(),
      Text::make('Email')->sortable()->get(),
      Password::make('Password')->get(),
//      BelongsTo::make('Role', false, 'display_name', 'id', Role::class, [['id', 1]])->sortable()->get(),
    ];
  }

}
