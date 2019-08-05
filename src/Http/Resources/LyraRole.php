<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Fields\HasMany;
use SertxuDeveloper\Lyra\Models\Role;
use SertxuDeveloper\Lyra\Models\User;

class LyraRole extends Resource {

  public static $model = Role::class;
  public static $search = ["id", "name", "email"];

  public $labels = [
    "singular" => "Role",
    "plural" => "Roles"
  ];

  public function fields() {

    return [
      Id::make('Id', 'id')->description('Campo autoincrementable')->primary(),
      Text::make('Key', 'name')->size(50)->sortable(),
      Text::make('Display Name', 'display_name')->size(50)->sortable(),
//      HasMany::make('Users', 'id')->setResource(LyraUser::class)->hideOnIndex(),
//      BelongsTo::make('Role', 'role_id')->setForeign('id', 'display_name', Role::class)->get(),
      HasMany::make('Users')->setResource(LyraUser::class)
    ];
  }
}
