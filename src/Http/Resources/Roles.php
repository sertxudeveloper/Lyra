<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;


class Roles extends Resource {

  public static $model = "SertxuDeveloper\Lyra\Models\Role";

  public $labels = [
    "singular" => "Roles",
    "plural" => "Roles"
  ];

  public function fields() {
    return [
      ID::make('ID', 'id')->get(),
      Text::make('Name', 'name')->sortable()->get(),
      Text::make('Display Name', 'display_name')->sortable()->get(),
//      Password::make('Username', 'name')->sortable()
    ];
  }

}
