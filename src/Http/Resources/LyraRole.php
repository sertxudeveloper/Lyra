<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use SertxuDeveloper\Lyra\Fields\HasMany;
use SertxuDeveloper\Lyra\Models\Role as Model;
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Text;

class LyraRole extends Resource
{
  public static $model = Model::class;
  public static $search = ["id", "name"];
  public static $title = 'name';
  public static $subtitle = 'email';
  public $singular = "User";
  public $plural = "Users";

  public function fields() {

    return [
      Id::make('Id')->sortable(),
      Text::make('Name')->rules('required'),
      Text::make('Users count', function () {
        return $this->users()->count();
      })->hideOnShow(),
      HasMany::make('Users')->setResource(LyraUser::class)
    ];
  }

}
