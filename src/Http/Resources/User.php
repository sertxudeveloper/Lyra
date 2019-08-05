<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\BelongsTo;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Models\Role;

class User extends Resource {

  public static $model = "App\User";
  public static $primary = "id";
  public static $search = ["id", "name", "email"];

  public function fields() {

    return [
      Id::make('Id', 'id')->description('Campo autoincrementable'),
      Text::make('Username', 'name')->size(50)->sortable(),
      Text::make('Email')->sortable(),
      Password::make('Password'),
//      BelongsTo::make('Role', 'role')
//      BelongsTo::make('Role', false, 'display_name', 'id', Role::class, [['id', 1]])->sortable()->get(),
    ];
  }

}
