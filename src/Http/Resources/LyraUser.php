<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\BelongsTo;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Models\Role;
use SertxuDeveloper\Lyra\Models\User;

class LyraUser extends Resource {

  public static $model = User::class;
  public static $search = ["id", "name", "email"];

  public $labels = [
    "singular" => "User",
    "plural" => "Users"
  ];

  public function fields() {

    return [
      Id::make('Id')->description('Campo autoincrementable')->primary(),
      Text::make('Name')->size(50)->sortable(),
      Text::make('Email')->description('Also used to log in')->sortable(),
      Text::make('Combo (Name - Email)', function ($model) {
        return $model->name . ' - ' . $model->email;
      }),
      Password::make('Password')->hideOnIndex()->hideOnShow(),
      BelongsTo::make('Role', 'role_id')->setForeign('id', 'display_name', Role::class),
    ];
  }

}
