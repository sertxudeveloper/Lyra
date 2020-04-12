<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\BelongsTo;
use SertxuDeveloper\Lyra\Fields\Image;
use SertxuDeveloper\Lyra\Fields\Select;
use SertxuDeveloper\Lyra\Models\User as Model;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;

class LyraUser extends Resource {

  public static $model = Model::class;

  public static $search = ["id", "name", "email"];
  public static $title = "name";
  public static $subtitle = "email";

  public $singular = "User";
  public $plural = "Users";

  public function fields() {

    return [
      Id::make('Id'),
      Text::make('Name')->rules('required'),
      Text::make('Email')->rules('required', "unique:lyra_users,email,{{id}}"),
      Password::make('Password')->rules('nullable', 'string', 'min:8'),
      Image::make('Avatar')->prunable(),
      BelongsTo::make('Role')->setResource(LyraRole::class)->rules('required'),
      Select::make('Theme', 'preferred_theme')->options(['default', 'dark', 'light'])->default('default')
    ];
  }

}
