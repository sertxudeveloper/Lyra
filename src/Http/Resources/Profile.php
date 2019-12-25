<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\BelongsTo;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Models\Role;
use SertxuDeveloper\Lyra\Models\User;

class Profile extends Resource {

  public static $model = User::class;
  public static $search = ["id", "name", "email"];

  public $labels = ["singular" => "User", "plural" => "Users"];

  public function fields() {

    return [
      Id::make('Id')->description('Campo autoincrementable'),
      Text::make('Name')->size(50)->sortable(),
      Text::make('Email')->description('Also used to log in')->sortable(),
      Text::make('Combo (Name - Email)', function ($model) {
        return $model->name . ' - ' . $model->email;
      }),
      Password::make('Password'),
      BelongsTo::make('Role')->setResource(Roles::class)->setDisplay('name'),
    ];
  }

}
