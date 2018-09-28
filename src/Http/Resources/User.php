<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

class User extends Resource {

  public static $group = "Users";
  public static $model = 'App\User';
  public $with = ['post'];

  public function fields() {
    return [];
  }

  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request) {
    return parent::toArray($request);
  }
}
